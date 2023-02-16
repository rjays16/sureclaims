<?php

/**
 * SupportingDocumentsController.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller as BaseController;
use App\Lib\Soap\EclaimsServiceInterface;
use App\Model\Entities\SupportingDocument;
use App\Model\Repositories\Contracts\ReturnDocumentRepository;
use App\Model\Repositories\Contracts\SupportingDocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

/**
 *
 * Description of SupportingDocumentsController
 *
 */

class SupportingDocumentsController extends BaseController
{

    /** @var SupportingDocumentRepository */
    private $supportingDocuments;

    /** @var ReturnDocumentRepository */
    private $returnDocuments;

    /** @var EclaimsServiceInterface */
    private $eclaimsService;

    /**
     * SupportingDocumentsController constructor.
     * @param SupportingDocumentRepository $supportingDocuments
     * @param ReturnDocumentRepository $returnDocuments
     * @param EclaimsServiceInterface $eclaimsService
     */
    public function __construct(
        SupportingDocumentRepository $supportingDocuments,
        ReturnDocumentRepository $returnDocuments,
        EclaimsServiceInterface $eclaimsService
    ) {
        $this->supportingDocuments = $supportingDocuments;
        $this->returnDocuments = $returnDocuments;
        $this->eclaimsService = $eclaimsService;
    }

    /**
     *
     */
    public function test()
    {
        $this->respondWithArray(['hello' => 'world']);
    }

    public function upload(Request $request)
    {
        try {
            // Validate file type
            try {
                $request->validate([
                    'document' => 'mimes:csv,xlx,xls,pdf,txt,xlsx,csv,xlsm,xlsb,xlr,xlw,xla|max:51200'
                ], [
                    'document.max' => 'The :attribute may not be greater than 50MB.'
                ]);

                if ($request->input('action') == 'upload_document') {
                    $validator = Validator::make(
                        $request->all(),
                        [
                            'file' => 'bail|required|mimes:application/xml,xml|max:51200',
                        ]
                    );
                    $validator->validate();
                }
                
            } catch(ValidationException $e) {
                throw $e;
            }

            $uid = Uuid::uuid4()->toString();
            $file = $request->file('document');
            $sourcePath = $file->path();
            $sourceHash = sha1_file($sourcePath);

            try {
                $result = $this->eclaimsService->documentUpload([
                    'name' => $file->getClientOriginalName(),
                    // add claim number before $uid
                    'owner' => call_user_func(config('eclaims.auto_document_own.encode'), $uid),
                    'contentType' => $file->getClientMimeType(),
                    'attachment' => file_get_contents($sourcePath),
                ]);
            } catch(\Exception $e) {
                throw $e;
            }

            $supportingDocument = $this->supportingDocuments->create([
                'storage_uid' => array_get($result, 'id'),
                'file_name' => array_get($result, 'name'),
                'file_size' => array_get($result, 'size'),
                'public_path' =>  $this->eclaimsService->documentURL(array_get($result, 'id')),
                'hash' => $sourceHash,
                'encrypted_hash' => array_get($result, 'hash'),
            ]);

            return $this->respondWithArray([
                'status' => 'success',
                'data' => $supportingDocument,
            ]);

        }
        catch (ValidationException $e) {
            return  $this->respondWithArray([
                'status' => 'validation',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $e->errors()
            ]);
        }
        catch (\Exception $e) {
            return  $this->respondWithArray([
                'status' => 'errors',
                'message' => get_class($e) . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * @param Request $request
     */
    public function uploadTest(Request $request)
    {
        try {
            $diskName = 'tenant';
            $uploadDir = '/supporting-documents/';
            $disk = \Storage::disk($diskName);
            $uid = Uuid::uuid4()->toString();

            $file = $request->file('document');
            $sourcePath = $file->path();
            $sourceHash = sha1_file($sourcePath);

            // Check if we want to keep raw file content
            if (config('eclaims.keep_raw_uploads', false)) {
                $ext = '.raw';
                $file->storeAs($uploadDir, $uid . $ext, [
                    'disk' => $diskName,
                    'visibility' => 'private'
                ]);
            }

            $destPath = tempnam(sys_get_temp_dir(), 'doc');
            $this->eclaimsService->encryptDocument($sourcePath, $destPath);
            $destHash = sha1_file($destPath);

            // Copy encrypted file to disk
            $disk->put(
                $uploadDir.$uid,
                @file_get_contents($destPath),
                [ 'visibility' => 'public' ]
            );


            $supportingDocument = $this->supportingDocuments->create([
                'storage_uid' => $uid,
                'file_name' => $file->getFilename(),
                'file_size' => $file->getClientSize(),
                'public_path' => url('/supporting-documents/'.$uid),
                'hash' => $sourceHash,
                'encrypted_hash' => $destHash
            ]);

            return $this->respondWithArray([
                'status' => 'success',
                'data' => $supportingDocument,
            ]);

        } catch (\Exception $e) {
            return  $this->respondWithArray([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}

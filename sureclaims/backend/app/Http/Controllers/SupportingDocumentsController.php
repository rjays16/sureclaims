<?php

/**
 * SupportingDocumentsController.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use App\Lib\Soap\EclaimsServiceInterface;
use App\Model\Entities\SupportingDocument;
use App\Model\Repositories\Contracts\SupportingDocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    /**
     * SupportingDocumentsController constructor.
     */
    public function __construct(
        SupportingDocumentRepository $supportingDocuments
    ) {
        $this->supportingDocuments = $supportingDocuments;
    }

    /**
     * @param string $uid
     * @param Request $request
     *
     * @return Response
     */
    public function download(string $uid, Request $request)
    {
        $diskName = 'tenant';
        $uploadDir = '/supporting-documents/';
        $disk = \Storage::disk($diskName);

        return response()->download($disk->path($uploadDir.$uid));
    }
}

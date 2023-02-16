<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ReturnDocumentCreateRequest;
use App\Http\Requests\ReturnDocumentUpdateRequest;
use App\Model\Repositories\Contracts\ReturnDocumentRepository;
use App\Model\Validators\ReturnDocumentValidator;

/**
 * Class ReturnDocumentsController.
 *
 * @package namespace App\Http\Controllers;
 */
class ReturnDocumentsController extends Controller
{
    /**
     * @var ReturnDocumentRepository
     */
    protected $repository;

    /**
     * @var ReturnDocumentValidator
     */
    protected $validator;

    /**
     * ReturnDocumentsController constructor.
     *
     * @param ReturnDocumentRepository $repository
     * @param ReturnDocumentValidator $validator
     */
    public function __construct(ReturnDocumentRepository $repository, ReturnDocumentValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $returnDocuments = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $returnDocuments,
            ]);
        }

        return view('returnDocuments.index', compact('returnDocuments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ReturnDocumentCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(ReturnDocumentCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $returnDocument = $this->repository->create($request->all());

            $response = [
                'message' => 'ReturnDocument created.',
                'data'    => $returnDocument->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $returnDocument = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $returnDocument,
            ]);
        }

        return view('returnDocuments.show', compact('returnDocument'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $returnDocument = $this->repository->find($id);

        return view('returnDocuments.edit', compact('returnDocument'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ReturnDocumentUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(ReturnDocumentUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $returnDocument = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'ReturnDocument updated.',
                'data'    => $returnDocument->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'ReturnDocument deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'ReturnDocument deleted.');
    }
}

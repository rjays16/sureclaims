<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ClaimStatusDetailCreateRequest;
use App\Http\Requests\ClaimStatusDetailUpdateRequest;
use App\Model\Repositories\Contracts\ClaimStatusDetailRepository;
use App\Model\Validators\ClaimStatusDetailValidator;

/**
 * Class ClaimStatusDetailsController.
 *
 * @package namespace App\Http\Controllers;
 */
class ClaimStatusDetailsController extends Controller
{
    /**
     * @var ClaimStatusDetailRepository
     */
    protected $repository;

    /**
     * @var ClaimStatusDetailValidator
     */
    protected $validator;

    /**
     * ClaimStatusDetailsController constructor.
     *
     * @param ClaimStatusDetailRepository $repository
     * @param ClaimStatusDetailValidator $validator
     */
    public function __construct(ClaimStatusDetailRepository $repository, ClaimStatusDetailValidator $validator)
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
        $claimStatusDetails = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $claimStatusDetails,
            ]);
        }

        return view('claimStatusDetails.index', compact('claimStatusDetails'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ClaimStatusDetailCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(ClaimStatusDetailCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $claimStatusDetail = $this->repository->create($request->all());

            $response = [
                'message' => 'ClaimStatusDetail created.',
                'data'    => $claimStatusDetail->toArray(),
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
        $claimStatusDetail = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $claimStatusDetail,
            ]);
        }

        return view('claimStatusDetails.show', compact('claimStatusDetail'));
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
        $claimStatusDetail = $this->repository->find($id);

        return view('claimStatusDetails.edit', compact('claimStatusDetail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ClaimStatusDetailUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(ClaimStatusDetailUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $claimStatusDetail = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'ClaimStatusDetail updated.',
                'data'    => $claimStatusDetail->toArray(),
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
                'message' => 'ClaimStatusDetail deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'ClaimStatusDetail deleted.');
    }
}

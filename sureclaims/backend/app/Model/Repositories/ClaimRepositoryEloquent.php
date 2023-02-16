<?php

namespace App\Model\Repositories;

use App\Model\Presenters\ClaimPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\ClaimRepository;
use App\Model\Entities\Claim;
use App\Model\Validators\ClaimValidator;

/**
 * Class ClaimRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class ClaimRepositoryEloquent
    extends BaseRepository
    implements ClaimRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Claim::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return ClaimPresenter::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return ClaimValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        // $this->pushCriteria(app(RequestCriteria::class));
    }

}

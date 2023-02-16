<?php

namespace App\Model\Repositories;

use App\Model\Presenters\SecondCaseRatePresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\SecondCaseRateRepository;
use App\Model\Entities\SecondCaseRate;
use App\Model\Validators\SecondCaseRateValidator;

/**
 * Class SecondCaseRateRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class SecondCaseRateRepositoryEloquent extends BaseRepository implements SecondCaseRateRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SecondCaseRate::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return SecondCaseRateValidator::class;
    }

    public function presenter()
    {
        return SecondCaseRatePresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
//        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}

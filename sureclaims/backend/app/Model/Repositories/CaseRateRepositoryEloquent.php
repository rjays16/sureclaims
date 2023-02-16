<?php

namespace App\Model\Repositories;

use App\Model\Presenters\CaseRatePresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\CaseRateRepository;
use App\Model\Entities\CaseRate;
use App\Model\Validators\CaseRateValidator;

/**
 * Class CaseRateRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class CaseRateRepositoryEloquent extends BaseRepository implements CaseRateRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CaseRate::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return CaseRateValidator::class;
    }

    public function presenter()
    {
        return CaseRatePresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
//        $this->pushCriteria( app( RequestCriteria::class ) );
    }

}

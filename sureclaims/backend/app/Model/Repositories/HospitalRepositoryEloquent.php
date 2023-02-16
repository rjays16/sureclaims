<?php

namespace App\Model\Repositories;

use App\Model\Presenters\HospitalPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\HospitalRepository;
use App\Model\Entities\Hospital;
use App\Model\Validators\HospitalValidator;

/**
 * Class HospitalRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class HospitalRepositoryEloquent
    extends BaseRepository
    implements HospitalRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Hospital::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return HospitalValidator::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return HospitalPresenter::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}

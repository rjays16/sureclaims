<?php

namespace App\Model\Repositories;

use App\Model\Presenters\DoctorPresenter;
use App\Model\Repositories\Contracts\DoctorRepository;
use App\Model\Entities\Doctor;
use App\Model\Validators\DoctorValidator;

/**
 * Class DoctorRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class DoctorRepositoryEloquent
    extends BaseRepository
    implements DoctorRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Doctor::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return DoctorValidator::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return DoctorPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        // $this->pushCriteria(app(RequestCriteria::class));
    }

}

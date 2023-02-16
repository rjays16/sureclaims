<?php
/**
 * EmployerRepositoryEloquent.php
 *
 * @author Jolly Caralos <jadcaralos@gmail.com>
 * @copyright (c) 2017, Segworks Technologies Corporation
 */


namespace App\Model\Repositories;

use App\Model\Entities\Employer;
use App\Model\Presenters\EmployerPresenter;
use App\Model\Repositories\Contracts\EmployerRepository;
use App\Model\Validators\EmployerValidator;

class EmployerRepositoryEloquent
    extends BaseRepository
    implements EmployerRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Employer::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {
        return EmployerValidator::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return EmployerPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        // $this->pushCriteria(app(RequestCriteria::class));
    }
}
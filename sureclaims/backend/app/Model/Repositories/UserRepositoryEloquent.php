<?php

namespace App\Model\Repositories;

use App\Model\Presenters\UserPresenter;
use App\User;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\UserRepository;
use App\Model\Validators\UserValidator;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class UserRepositoryEloquent
    extends BaseRepository
    implements UserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return UserPresenter::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return UserValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}

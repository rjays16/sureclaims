<?php

namespace App\Model\Repositories;

use App\Model\Presenters\UserProfilePresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\UserProfileRepository;
use App\Model\Entities\UserProfile;
use App\Model\Validators\UserProfileValidator;

/**
 * Class UserProfileRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class UserProfileRepositoryEloquent
    extends BaseRepository
    implements UserProfileRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserProfile::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return UserProfilePresenter::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return UserProfileValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}

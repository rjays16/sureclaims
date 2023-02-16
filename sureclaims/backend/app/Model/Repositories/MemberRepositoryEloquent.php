<?php

namespace App\Model\Repositories;

use App\Model\Presenters\MemberPresenter;
use App\Model\Repositories\Contracts\MemberRepository;
use App\Model\Entities\Member;
use App\Model\Validators\MemberValidator;

/**
 * Class MemberRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class MemberRepositoryEloquent
    extends BaseRepository
    implements MemberRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Member::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return MemberValidator::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return MemberPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        // $this->pushCriteria(app(RequestCriteria::class));
    }

}

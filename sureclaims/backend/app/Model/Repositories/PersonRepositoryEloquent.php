<?php

namespace App\Model\Repositories;

use App\Model\Presenters\PersonPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\PersonRepository;
use App\Model\Entities\Person;
use App\Model\Validators\PersonValidator;

/**
 * Class PersonRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class PersonRepositoryEloquent
    extends BaseRepository
    implements PersonRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Person::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return PersonPresenter::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return PersonValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        // $this->pushCriteria(app(RequestCriteria::class));
    }

}

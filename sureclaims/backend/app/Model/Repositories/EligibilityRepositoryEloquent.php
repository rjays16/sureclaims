<?php

namespace App\Model\Repositories;

use App\Model\Presenters\EligibilityPresenter;
use App\Model\Repositories\Contracts\EligibilityRepository;
use App\Model\Entities\Eligibility;
use App\Model\Validators\EligibilityValidator;

/**
 * Class EligibilityRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class EligibilityRepositoryEloquent
    extends BaseRepository
    implements EligibilityRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Eligibility::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return EligibilityValidator::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return EligibilityPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        // $this->pushCriteria(app(RequestCriteria::class));
    }

}

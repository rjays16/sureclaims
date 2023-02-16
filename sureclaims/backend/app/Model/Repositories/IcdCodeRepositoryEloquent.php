<?php

namespace App\Model\Repositories;

use App\Model\Presenters\IcdCodePresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\IcdCodeRepository;
use App\Model\Entities\IcdCode;
use App\Model\Validators\IcdCodeValidator;

/**
 * Class IcdCodeRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class IcdCodeRepositoryEloquent
    extends BaseRepository
    implements IcdCodeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return IcdCode::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return IcdCodeValidator::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return IcdCodePresenter::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        // $this->pushCriteria(app(RequestCriteria::class));
    }

}

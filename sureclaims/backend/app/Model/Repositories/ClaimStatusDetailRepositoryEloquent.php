<?php

namespace App\Model\Repositories;

use App\Model\Presenters\ClaimStatusDetailPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\ClaimStatusDetailRepository;
use App\Model\Entities\ClaimStatusDetail;
use App\Model\Validators\ClaimStatusDetailValidator;


/**
 * Class ClaimStatusDetailRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class ClaimStatusDetailRepositoryEloquent extends BaseRepository implements ClaimStatusDetailRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ClaimStatusDetail::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return ClaimStatusDetailPresenter::class;
    }

        /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ClaimStatusDetailValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
//        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}

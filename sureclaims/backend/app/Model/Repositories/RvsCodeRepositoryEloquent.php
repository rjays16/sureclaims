<?php

namespace App\Model\Repositories;

use App\Model\Presenters\RvsCodePresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\RvsCodeRepository;
use App\Model\Entities\RvsCode;
use App\Model\Validators\RvsCodeValidator;

/**
 * Class RvsCodeRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class RvsCodeRepositoryEloquent
    extends BaseRepository
    implements RvsCodeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RvsCode::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return RvsCodeValidator::class;
    }



    public function presenter()
    {
        return RvsCodePresenter::class;
    }
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
//        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}

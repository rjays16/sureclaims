<?php

namespace App\Model\Repositories;

use App\Model\Presenters\HciPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\HciRepository;
use App\Model\Entities\Hci;
use App\Model\Validators\HciValidator;

/**
 * Class HciRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class HciRepositoryEloquent extends BaseRepository implements HciRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Hci::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return HciValidator::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return HciPresenter::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}

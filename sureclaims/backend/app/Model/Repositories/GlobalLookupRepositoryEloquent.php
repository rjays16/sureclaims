<?php

namespace App\Model\Repositories;

use App\Model\Presenters\GlobalLookupPresenter;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Model\Repositories\Contracts\GlobalLookupRepository;
use App\Model\Entities\GlobalLookup;

/**
 * Class GlobalLookupRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class GlobalLookupRepositoryEloquent
    extends BaseRepository
    implements GlobalLookupRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return GlobalLookup::class;
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return GlobalLookupPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        // $this->pushCriteria(app(RequestCriteria::class));
    }

}

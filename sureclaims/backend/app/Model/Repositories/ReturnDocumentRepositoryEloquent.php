<?php

namespace App\Model\Repositories;

use App\Model\Presenters\ReturnDocumentPresenter;
use App\Model\Repositories\Contracts\ReturnDocumentRepository;
use App\Model\Entities\ReturnDocument;
use App\Model\Validators\ReturnDocumentValidator;

/**
 * Class ReturnDocumentRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class ReturnDocumentRepositoryEloquent extends BaseRepository implements ReturnDocumentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ReturnDocument::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return ReturnDocumentValidator::class;
    }

    /**
     * @return string
     */
    public function presenter()
    {
        return ReturnDocumentPresenter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
//        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}

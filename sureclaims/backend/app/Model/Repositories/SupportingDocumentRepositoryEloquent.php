<?php

namespace App\Model\Repositories;

use App\Model\Presenters\SupportingDocumentPresenter;
use App\Model\Repositories\Contracts\SupportingDocumentRepository;
use App\Model\Entities\SupportingDocument;
use App\Model\Validators\SupportingDocumentValidator;

/**
 * Class SupportingDocumentRepositoryEloquent.
 *
 * @package namespace App\Model\Repositories;
 */
class SupportingDocumentRepositoryEloquent
    extends BaseRepository
    implements SupportingDocumentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SupportingDocument::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return SupportingDocumentValidator::class;
    }

    /**
     * @return string
     */
    public function presenter()
    {
        return SupportingDocumentPresenter::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        // $this->pushCriteria(app(RequestCriteria::class));
    }

}

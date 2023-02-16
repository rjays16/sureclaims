<?php

namespace App\Model\Presenters;

use App\Model\Transformers\SupportingDocumentTransformer;

/**
 * Class SupportingDocumentPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class SupportingDocumentPresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'supportingDocument';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'supportingDocuments';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new SupportingDocumentTransformer();
    }
}

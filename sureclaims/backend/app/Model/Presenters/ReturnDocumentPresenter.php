<?php

namespace App\Model\Presenters;

use App\Model\Transformers\ReturnDocumentTransformer;

/**
 * Class ReturnDocumentPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class ReturnDocumentPresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'returnDocument';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'returnDocuments';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ReturnDocumentTransformer();
    }
}

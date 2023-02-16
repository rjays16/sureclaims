<?php

namespace App\Model\Presenters;

use App\Model\Transformers\GlobalLookupTransformer;

/**
 * Class GlobalLookupPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class GlobalLookupPresenter extends BasePresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new GlobalLookupTransformer();
    }
}

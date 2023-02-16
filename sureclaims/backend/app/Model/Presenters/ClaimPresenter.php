<?php

namespace App\Model\Presenters;

use App\Model\Transformers\ClaimTransformer;

/**
 * Class ClaimPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class ClaimPresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'claim';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'claims';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ClaimTransformer();
    }
}

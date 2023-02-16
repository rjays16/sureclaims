<?php

namespace App\Model\Presenters;

use App\Model\Transformers\ClaimStatusDetailTransformer;

/**
 * Class ClaimStatusDetailPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class ClaimStatusDetailPresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'claimStatusDetail';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'claimStatusDetails';
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ClaimStatusDetailTransformer();
    }
}

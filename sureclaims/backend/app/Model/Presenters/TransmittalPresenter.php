<?php

namespace App\Model\Presenters;

use App\Model\Transformers\TransmittalTransformer;

/**
 * Class TransmittalPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class TransmittalPresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'transmittal';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'transmittals';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TransmittalTransformer();
    }
}

<?php

namespace App\Model\Presenters;

use App\Model\Transformers\SecondCaseRateTransformer;

/**
 * Class SecondCaseRatePresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class SecondCaseRatePresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'secondCaseRate';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'secondCaseRates';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new SecondCaseRateTransformer();
    }
}

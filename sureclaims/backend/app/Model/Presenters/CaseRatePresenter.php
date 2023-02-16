<?php

namespace App\Model\Presenters;

use App\Model\Transformers\CaseRateTransformer;

/**
 * Class CaseRatePresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class CaseRatePresenter extends BasePresenter
{

    /**
     * @var string
     */
    protected $resourceKeyItem = 'caseRate';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'caseRates';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CaseRateTransformer();
    }
}

<?php

namespace App\Model\Presenters;

use App\Model\Transformers\EligibilityTransformer;

/**
 * Class EligibilityPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class EligibilityPresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'eligibility';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'eligibilities';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new EligibilityTransformer();
    }
}

<?php

namespace App\Model\Presenters;

use App\Model\Transformers\HospitalTransformer;

/**
 * Class HospitalPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class HospitalPresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'hospital';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'hospitals';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new HospitalTransformer();
    }
}

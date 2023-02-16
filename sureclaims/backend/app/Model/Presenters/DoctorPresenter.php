<?php

namespace App\Model\Presenters;

use App\Model\Transformers\DoctorTransformer;

/**
 * Class DoctorPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class DoctorPresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'doctor';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'doctors';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new DoctorTransformer();
    }
}

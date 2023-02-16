<?php

namespace App\Model\Presenters;

use App\Model\Transformers\HciTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class HciPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class HciPresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'hci';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'hcis';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new HciTransformer();
    }
}

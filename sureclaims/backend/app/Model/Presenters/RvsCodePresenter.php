<?php

namespace App\Model\Presenters;

use App\Model\Transformers\RvsCodeTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class RvsCodePresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class RvsCodePresenter extends BasePresenter
{

    /**
     * @var string
     */
    protected $resourceKeyItem = 'rvsCode';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'rvsCodes';


    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new RvsCodeTransformer();
    }
}

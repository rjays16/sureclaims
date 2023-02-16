<?php

namespace App\Model\Presenters;

use App\Model\Transformers\IcdCodeTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class IcdCodePresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class IcdCodePresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'icdCode';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'icdCodes';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new IcdCodeTransformer();
    }
}

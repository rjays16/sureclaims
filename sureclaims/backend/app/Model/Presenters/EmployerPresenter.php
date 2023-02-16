<?php

namespace App\Model\Presenters;

use App\Model\Transformers\EmployerTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class EmployerPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class EmployerPresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'employer';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'employers';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new EmployerTransformer();
    }
}

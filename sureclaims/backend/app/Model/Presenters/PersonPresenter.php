<?php

namespace App\Model\Presenters;

use App\Model\Transformers\PersonTransformer;

/**
 * Class PersonPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class PersonPresenter extends BasePresenter
{

    /**
     * @var string
     */
    protected $resourceKeyItem = 'person';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'persons';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PersonTransformer();
    }
}

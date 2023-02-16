<?php

namespace App\Model\Presenters;

use App\Model\Transformers\UserTransformer;

/**
 * Class UserPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class UserPresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'user';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'users';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserTransformer();
    }
}

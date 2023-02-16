<?php

namespace App\Model\Presenters;

use App\Model\Transformers\UserProfileTransformer;

/**
 * Class UserProfilePresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class UserProfilePresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'userProfile';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'userProfiles';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserProfileTransformer();
    }
}

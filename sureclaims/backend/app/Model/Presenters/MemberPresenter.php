<?php

namespace App\Model\Presenters;

use App\Model\Transformers\MemberTransformer;

/**
 * Class MemberPresenter.
 *
 * @package namespace App\Model\Presenters;
 */
class MemberPresenter extends BasePresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'member';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'members';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new MemberTransformer();
    }
}

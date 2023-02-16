<?php

namespace App\Model\Transformers;

use League\Fractal\TransformerAbstract;
use App\Model\Entities\IcdCode;

/**
 * Class IcdCodeTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class IcdCodeTransformer extends TransformerAbstract
{
    /**
     * Transform the IcdCode entity.
     *
     * @param \App\Model\Entities\IcdCode $model
     *
     * @return array
     */
    public function transform(IcdCode $model)
    {
        return [
            'id' => (int) $model->id,

            /* place your other model properties here */
            'code' => $model->code,
            'description' => $model->description,

            'createdAt' => ec_to_datetime($model->created_at),
            'updatedAt' => ec_to_datetime($model->updated_at),
        ];
    }
}

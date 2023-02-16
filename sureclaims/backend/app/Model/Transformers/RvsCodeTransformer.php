<?php

namespace App\Model\Transformers;

use League\Fractal\TransformerAbstract;
use App\Model\Entities\RvsCode;

/**
 * Class RvsCodeTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class RvsCodeTransformer extends TransformerAbstract
{
    /**
     * Transform the RvsCode entity.
     *
     * @param \App\Model\Entities\RvsCode $model
     *
     * @return array
     */
    public function transform(RvsCode $model)
    {
        return [
            'id'              => (int)$model->id,

            /* place your other model properties here */
            'code'            => $model->code,
            'description'     => $model->description,
            'rvu'             => $model->rvu,
            'with_laterality' => $model->with_laterality,
            'createdAt'       => ec_to_datetime($model->created_at),
            'updatedAt'       => ec_to_datetime($model->updated_at),
        ];
    }
}

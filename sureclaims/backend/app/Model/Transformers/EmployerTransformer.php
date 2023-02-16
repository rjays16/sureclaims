<?php

namespace App\Model\Transformers;

use League\Fractal\TransformerAbstract;
use App\Model\Entities\Employer;

/**
 * Class EmployerTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class EmployerTransformer extends TransformerAbstract
{
    /**
     * Transform the Employer entity.
     *
     * @param \App\Model\Entities\Employer $model
     *
     * @return array
     */
    public function transform( Employer $model )
    {
        return [
            'id' => (int)$model->id,
            'pen' => (string)$model->pen,
            'name' => (string)$model->name,
            'address' => (string)$model->address,
            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}

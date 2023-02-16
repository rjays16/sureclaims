<?php

namespace App\Model\Transformers;

use League\Fractal\TransformerAbstract;
use App\Model\Entities\Doctor;

/**
 * Class DoctorTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class DoctorTransformer extends TransformerAbstract
{
    /**
     * Transform the Doctor entity.
     *
     * @param \App\Model\Entities\Doctor $model
     *
     * @return array
     */
    public function transform(Doctor $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */
            'pan' => (string) $model->pan,
            'tin' => (string) $model->tin,
            'lastName' => (string) $model->last_name,
            'firstName' => (string) $model->first_name,
            'middleName' => (string) $model->middle_name,
            'suffix' => (string) $model->suffix,
            'birthDate' => \ec_to_date($model->birth_date),

            'createdAt' => \ec_to_datetime($model->created_at),
            'updatedAt' => \ec_to_datetime($model->updated_at),
        ];
    }
}

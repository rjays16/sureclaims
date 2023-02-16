<?php

namespace App\Model\Transformers;

use League\Fractal\TransformerAbstract;
use App\Model\Entities\Hci;

/**
 * Class HciTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class HciTransformer extends TransformerAbstract
{
    /**
     * Transform the Hci entity.
     *
     * @param \App\Model\Entities\Hci $model
     *
     * @return array
     */
    public function transform(Hci $model)
    {
        return [
            'id' => (int)$model->id,
            'hospitalName' => $model->hospital_name,
            'pmccNo' => (string) $model->pmcc_no,
            'accreditationCode' => $model->accreditation_code,
            'createdAt' => ec_to_date($model->created_at),
            'updatedAt' =>  ec_to_date($model->updated_at)
        ];
    }
}

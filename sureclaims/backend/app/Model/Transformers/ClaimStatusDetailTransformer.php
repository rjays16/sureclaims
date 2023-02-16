<?php

namespace App\Model\Transformers;

use App\Model\Entities\ClaimStatusDetail;
use League\Fractal\TransformerAbstract;

/**
 * Class ClaimStatusDetailTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class ClaimStatusDetailTransformer extends TransformerAbstract
{
    /**
     * Transform the ClaimStatusDetail entity.
     *
     * @param \App\Model\Entities\ClaimStatusDetail $model
     *
     * @return array
     */
    public function transform(ClaimStatusDetail $model)
    {
        return [
            'id' => (int)$model->id,

            /* place your other model properties here */
            'patientId' => $model->patient_id,
            'claimId' => $model->claim_id,
            'dateInquiry' => $model->date_inquiry,
            'asOfDate' => $model->as_of_date,
            'asOfTime' => $model->as_of_time,
            'claimDateRefile' => $model->claim_date_refile,
            'claimDateReceived' => $model->claim_date_received,

            'createdAt' => ec_to_datetime($model->created_at),
            'updatedAt' => ec_to_datetime($model->updated_at)
        ];
    }
}

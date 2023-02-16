<?php

namespace App\Model\Transformers;

use League\Fractal\TransformerAbstract;
use App\Model\Entities\SecondCaseRate;

/**
 * Class SecondCaseRateTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class SecondCaseRateTransformer extends TransformerAbstract
{
    /**
     * Transform the SecondCaseRate entity.
     *
     * @param \App\Model\Entities\SecondCaseRate $model
     *
     * @return array
     */
    public function transform( SecondCaseRate $model )
    {
        return [
            'id' => (int)$model->id,
            'code' => (string)$model->code,
            'description' => trim( $model->description, '" \t\n\r\0\x0B\\N"' ),
            'itemCode' => (string)$model->item_code,
            'itemDescription' => trim( $model->item_description, '" \t\n\r\0\x0B\\N"' ),
            'hciFee' => (double)$model->hci_fee,
            'profFee' => (double)$model->prof_fee,
            'amount' => (float)$model->amount,
            'effectivityDate' => (string)$model->effectivity_date,
            'caseType' => $model->case_type,
            'isIcd' => $model->case_type === SecondCaseRate::CASETYPE_ICD,
            'isRvs' => $model->case_type === SecondCaseRate::CASETYPE_RVS,
            'createdAt' => (string)( $model->created_at ),
            'updatedAt' => (string)( $model->updated_at ),
        ];
    }
}

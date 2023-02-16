<?php

namespace App\Model\Transformers;

use League\Fractal\TransformerAbstract;
use App\Model\Entities\CaseRate;

/**
 * Class CaseRateTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class CaseRateTransformer extends TransformerAbstract
{
    /**
     * Transform the CaseRate entity.
     *
     * @param \App\Model\Entities\CaseRate $model
     *
     * @return array
     */
    public function transform( CaseRate $model )
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
            'allowSecond' => (boolean)$model->allow_second,
            'secondaryHciFee' => (double)$model->secondary_hci_fee,
            'secondaryProfFee' => (double)$model->secondary_prof_fee,
            'secondaryAmount' => (double)$model->secondaryAmount,
            'effectivityDate' => (string)$model->effectivity_date,
            'caseType' => $model->case_type,
            'isIcd' => $model->case_type === CaseRate::CASETYPE_ICD,
            'isRvs' => $model->case_type === CaseRate::CASETYPE_RVS,
            'createdAt' => (string)( $model->created_at ),
            'updatedAt' => (string)( $model->updated_at ),
        ];
    }
}

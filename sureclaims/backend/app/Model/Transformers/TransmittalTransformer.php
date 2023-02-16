<?php

namespace App\Model\Transformers;

use App\User;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use App\Model\Entities\Transmittal;

/**
 * Class TransmittalTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class TransmittalTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'claims',
    ];

    /**
     * Transform the Transmittal entity.
     *
     * @param \App\Model\Entities\Transmittal $model
     *
     * @return array
     */
    public function transform(Transmittal $model)
    {
        return [
            'id' => (int)$model->id,

            /* place your other model properties here */
            'transmittalNo' => (string)$model->transmittal_no,
            'transmittalControlCode' => (string)$model->transmittal_control_code,
            'transmitDate' => ec_to_date($model->transmit_date),
            'transmitTime' => ec_to_time($model->transmit_time),
            'receiptTicketNo' => (string)$model->receipt_ticket_no,
            'notes' => (string)$model->notes,
            'transmitErrors' => (string)$model->transmit_errors,
            'autoTransmit' => $model->auto_transmit,
            'status' => $model->status,
            'isValid' => $model->is_valid,

            'byTransmittalPdf' => url("document/byTransmittalPdf/{$model->id}"),
            'byTransmittalExcel' => url("document/byTransmittalExcel/{$model->id}"),

            'createdAt' => ec_to_datetime($model->created_at),
            'updatedAt' => ec_to_datetime($model->updated_at),

            'createdBy' => $model->whoCreate(),
            'updatedBy' => $model->whoUpdate(),
        ];
    }

    /**
     * @param Transmittal $transmittal
     *
     * @return Collection
     */
    public function includeClaims(Transmittal $transmittal)
    {
        return $this->collection(
            $transmittal->claims,
            new ClaimTransformer(),
            ''
        );
    }

}

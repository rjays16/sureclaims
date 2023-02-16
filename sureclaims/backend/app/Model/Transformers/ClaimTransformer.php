<?php

namespace App\Model\Transformers;

use Illuminate\Support\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use App\Model\Entities\Claim;

/**
 * Class ClaimTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class ClaimTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'patient',
        'transmittal',
        'supportingDocuments',
        'claimStatusDetail',
        'returnDocuments',
    ];

    /**
     * Transform the Claim entity.
     *
     * @param \App\Model\Entities\Claim|Collection $model
     *
     * @return array
     */
    public function transform(Claim $model)
    {
        return [
            'id' => (int) $model->id,
            'transmittalId' => (string) $model->transmittal_id,
            'claimNumber' => $model->claim_no,
            'patientId' => $model->patient ? (string) $model->patient->id : '',
            'lastName' => $model->patient ? (string) $model->patient->last_name : '',
            'firstName' => $model->patient ? (string) $model->patient->first_name : '',
            'middleName' => $model->patient ? (string) $model->patient->middle_name : '',
            'suffix' => $model->patient ? (string) $model->patient->suffix : '',
            'birthDate' => $model->patient ? ec_to_date($model->patient->birthDate) : '',

            'admissionDate' => ec_to_date($model->admission_date),
            'dischargeDate' => ec_to_date($model->discharge_date),

            'lhioSeriesNo' => $model->lhio_series_no,
            'status' => $model->status,
            'data' => $model->data_json,
            'xml' => $model->data_xml,
            'xmlLink' => url("/claims/download/{$model->id}.xml"),
            'isValid' => $model->is_valid,
            'validationErrors' => $model->validation_errors,

            'createdAt' => ec_to_datetime($model->created_at),
            'updatedAt' => ec_to_datetime($model->updated_at),
        ];
    }

    /**
     * @param Claim $claim
     *
     * @return Item
     */
    public function includePatient(Claim $claim)
    {
        if (!$claim->patient) {
            return null;
        }
        return $this->item($claim->patient, new PersonTransformer());
    }

    /**
     * @param Claim $claim
     *
     * @return Item
     */
    public function includeTransmittal(Claim $claim)
    {
        if (!$claim->transmittal) {
            return null;
        }
        return $this->item($claim->transmittal, new TransmittalTransformer());
    }

    /**
     * @param Claim $claim
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeSupportingDocuments(Claim $claim)
    {
        return $this->collection(
            $claim->supportingDocuments,
            new SupportingDocumentTransformer(),
            ''
        );
    }

    /**
     * @param Claim $claim
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeReturnDocuments(Claim $claim)
    {
        return $this->collection(
            $claim->returnDocuments,
            new ReturnDocumentTransformer(),
            ''
        );
    }

    /**
     * @param Claim $claim
     *
     * @return Item
     */
    public function includeClaimStatusDetail(Claim $claim)
    {
        if (!$claim->claimStatusDetail) {
            return null;
        }
        return $this->item($claim->claimStatusDetail, new ClaimStatusDetailTransformer());
    }
}

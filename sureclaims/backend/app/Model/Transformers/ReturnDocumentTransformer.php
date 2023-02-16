<?php

namespace App\Model\Transformers;

use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use App\Model\Entities\ReturnDocument;

/**
 * Class ReturnDocumentTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class ReturnDocumentTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'supportingDocument',
    ];

    /**
     * Transform the ReturnDocument entity.
     *
     * @param ReturnDocument $model
     *
     * @return array
     */
    public function transform(ReturnDocument $model)
    {
        return [
            'id' => (string) $model->id,

            /* place your other model properties here */
            'patientId' => (string) $model->patient_id,
            'claimId' => (string) $model->claim_id,
            'supportDocumentId' => (string) $model->support_document_id,
            'isUploaded' => $model->is_uploaded,

            'createdAt' => ec_to_datetime($model->created_at),
            'updatedAt' => ec_to_datetime($model->updated_at)
        ];
    }

    /**
     * @param ReturnDocument $returnDocument
     * @return Item
     */
    public function includeSupportingDocument(ReturnDocument $returnDocument)
    {
        if (!$returnDocument->supportingDocument) {
            return null;
        }
        return $this->item($returnDocument->supportingDocument, new SupportingDocumentTransformer());
    }

}

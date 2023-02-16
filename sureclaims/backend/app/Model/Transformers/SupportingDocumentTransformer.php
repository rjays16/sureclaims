<?php

namespace App\Model\Transformers;

use League\Fractal\TransformerAbstract;
use App\Model\Entities\SupportingDocument;

/**
 * Class SupportingDocumentTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class SupportingDocumentTransformer extends TransformerAbstract
{
    /**
     * Transform the SupportingDocument entity.
     *
     * @param \App\Model\Entities\SupportingDocument $model
     *
     * @return array
     */
    public function transform(SupportingDocument $model)
    {
        return [
            'id' => (string) $model->id,

            /* place your other model properties here */
            'patientId' => (string) $model->patient_id,
            'claimId' => (string) $model->claim_id,
            'storageUid' => (string) $model->storage_uid,
            'documentType' => (string) $model->document_type,
            'fileName' => (string) $model->file_name,
            'fileSize' => (string) $model->file_size,
            'publicPath' => (string) $model->public_path,
            'hash' => (string) $model->hash,
            'encryptedHash' => (string) $model->encrypted_hash,

            'createdAt' => ec_to_datetime($model->created_at),
            'updatedAt' => ec_to_datetime($model->updated_at)
        ];
    }
}

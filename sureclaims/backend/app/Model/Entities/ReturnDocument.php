<?php

namespace App\Model\Entities;

use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ReturnDocument.
 *
 * @property int $id
 * @property int $patient_id
 * @property int $claim_id
 * @property int $support_document_id
 * @property string $is_uploaded
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Claim $claim
 * @property SupportingDocument $supportingDocument
 * @package namespace App\Model\Entities;
 */
class ReturnDocument extends Model implements Transformable
{
    use TransformableTrait,
        UsesTenantConnection;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id',
        'claim_id',
        'support_document_id',
        'is_uploaded',
    ];

    /**
     * @return BelongsTo
     */
    public function claim() : BelongsTo
    {
        return $this->belongsTo(Claim::class, 'claim_id');
    }

    /**
     * @return BelongsTo
     */
    public function supportingDocument() : BelongsTo
    {
        return $this->belongsTo(SupportingDocument::class, 'support_document_id');
    }

}

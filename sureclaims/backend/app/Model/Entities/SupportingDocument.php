<?php

namespace App\Model\Entities;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\Exceptions\InvalidXMLException;
use App\Model\Repositories\Contracts\ClaimRepository;
use App\Model\Transformers\ClaimXmlTransformer;
use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class SupportingDocument.
 *
 * @package namespace App\Model\Entities;
 *
 * @property int $id
 * @property int $patient_id
 * @property int $claim_id
 * @property string $storage_uid
 * @property string $document_type
 * @property string $file_name
 * @property string $file_size
 * @property string $public_path
 * @property string $hash
 * @property string $encrypted_hash
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Claim $claim
 * @property ReturnDocument $returnDocument
 */
class SupportingDocument extends Model implements Transformable
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
        'storage_uid',
        'document_type',
        'file_name',
        'file_size',
        'public_path',
        'hash',
        'encrypted_hash',
    ];



    /**
     *
     */
    public static function boot()
    {
        parent::boot();

        // After save
        self::created(function ($document) {
            static::postProcessDocument($document);
        });

        self::updated(function ($document) {
            static::postProcessDocument($document);
        });

        // Before update
        self::deleted(function ($document) {
            static::postProcessDocument($document);
        });

        static::deleting(function($document) { // before delete() method call this
            $document->returnDocument()->delete();
            // do the rest of the cleanup...
        });
    }

    /**
     * @return BelongsTo
     */
    public function claim() : BelongsTo
    {
        return $this->belongsTo(Claim::class, 'claim_id');
    }

    /**
     * @return HasOne
     */
    public function returnDocument() : HasOne
    {
        return $this->hasOne(ReturnDocument::class, 'support_document_id');
    }

    /**
     *
     */
    private static function postProcessDocument(SupportingDocument $document) : void
    {
        $claims = \App::make(ClaimRepository::class);
        try {
            /** @var Claim $claim */
            $claim = $claims->skipPresenter()->find($document->claim_id);
            $transformer = new ClaimXmlTransformer();
            $xmlData = $transformer->transform($claim);

            $claim->data_xml = $xmlData['xml'];
            $claim->is_valid = true;

            $service = \App::make(EclaimsServiceInterface::class);

            try {
                $service->validateClaimXML($claim->data_xml);
            } catch (InvalidXMLException $e) {
                $claim->is_valid = false;
                $claim->validation_errors = \GuzzleHttp\json_encode($e->validationErrors());
            } catch (\Exception $e) {
                $claim->is_valid = false;
                $claim->validation_errors = \GuzzleHttp\json_encode([$e->getMessage()]);
            }
            $claim->save();
        } catch (\Exception $e) {
        }
    }
}

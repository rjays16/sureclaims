<?php

/**
 * Claim.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Model\Entities;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\Exceptions\InvalidXMLException;
use App\Model\Factory\ClaimNumberFactory;
use App\Model\Transformers\ClaimXmlTransformer;
use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use App\Model\Concerns\Claim\ClaimScopes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Wildside\Userstamps\Userstamps;
use App\Model\Syncers\ClaimDataSyncer;

/**
 * Class Claim.
 *
 * @property int $patient_id
 * @property int $transmittal_id
 * @property string $claim_no
 * @property string $lhio_series_no
 * @property Carbon $admission_date
 * @property Carbon $discharge_date
 * @property string $status
 * @property string $data_json
 * @property string $data_xml
 * @property string $is_valid
 * @property string $validation_errors
 * @property int $rawClaimNo
 *
 * @property Person $patient
 * @property Transmittal $transmittal
 * @property Collection $supportingDocuments
 * @property ClaimStatusDetail $claimStatusDetail
 * @property Collection $returnDocuments
 *
 * @package namespace App\Model\Entities;
 */
class Claim extends Model implements Transformable
{
    use SoftDeletes,
        TransformableTrait,
        UsesTenantConnection,
        Userstamps,
        ClaimScopes;

    const STATUS_NOT_FOUND = 'CLAIM_SERIES_NOT_FOUND';
    const STATUS_IN_PROCESS = 'IN_PROCESS';
    const STATUS_RETURN = 'RETURN';
    const STATUS_DENIED = 'DENIED';
    const STATUS_WITH_CHEQUE = 'WITH_CHEQUE';
    const STATUS_WITH_VOUCHER = 'WITH_VOUCHER';
    const STATUS_VOUCHERING = 'VOUCHERING';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     *
     */
    protected $fillable = [
        'transmittal_id',
        'claim_no',
        'admission_date',
        'discharge_date',
        'status',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'admission_date',
        'discharge_date',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_valid' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo( Person::class, 'patient_id' );
    }

    /**
     * @return BelongsTo
     */
    public function transmittal(): BelongsTo
    {
        return $this->belongsTo( Transmittal::class, 'transmittal_id' );
    }

    /**
     * @return HasMany
     */
    public function supportingDocuments(): HasMany
    {
        return $this->hasMany( SupportingDocument::class, 'claim_id' );
    }

    /**
     * @return HasMany
     */
    public function returnDocuments(): HasMany
    {
        return $this->hasMany( ReturnDocument::class, 'claim_id' );
    }

    /**
     * @return HasOne
     */
    public function claimStatusDetail(): HasOne
    {
        return $this->hasOne( ClaimStatusDetail::class, 'claim_id' )
            ->orderBy('date_inquiry','desc');
    }

    /**
     *
     */
    public static function boot()
    {
        parent::boot();

        // Before save
        // self::creating( function ( $model ) {
        //     static::processData( $model );
        // } );

        // Before update
        // self::updating( function ( $model ) {
        //     static::processData( $model );
        // } );

        self::saving( function ( $model ) {
            static::processData( $model );
        } );
    }

    /**
     *
     */
    private static function processData( Claim $model ): void
    {
        $transformer = new ClaimXmlTransformer();
        $syncer = new ClaimDataSyncer($model);

        $model->data_json = $syncer->updated()->toJSON();
        $xmlData = $transformer->transform( $model );
        $model->data_xml = $xmlData[ 'xml' ];
        $model->is_valid = true;
        $model->admission_date = ec_from_date( array_get( $xmlData[ 'json' ], 'CF2.pAdmissionDate', null ) );
        $model->discharge_date = ec_from_date( array_get( $xmlData[ 'json' ], 'CF2.pDischargeDate', null ) );
        $service = \App::make( EclaimsServiceInterface::class );

        try {
            $service->validateClaimXML( $model->data_xml );
            $model->validation_errors = null;
        } catch ( InvalidXMLException $e ) {
            $model->is_valid = false;
            $model->validation_errors = \GuzzleHttp\json_encode( $e->validationErrors() );
        } catch ( \Exception $e ) {
            $model->is_valid = false;
            $model->validation_errors = \GuzzleHttp\json_encode( [ $e->getMessage() ] );
        }
    }

    /**
     * @param string $rawStatus
     *
     * @return string
     */
    public static function formatValidStatus( string $rawStatus ): ?string
    {
        $statuses = [
            'CLAIM SERIES NOT FOUND' => self::STATUS_NOT_FOUND,
            'IN PROCESS' => self::STATUS_IN_PROCESS,
            'RETURN' => self::STATUS_RETURN,
            'DENIED' => self::STATUS_DENIED,
            'WITH CHEQUE' => self::STATUS_WITH_CHEQUE,
            'WITH VOUCHER' => self::STATUS_WITH_VOUCHER,
            'VOUCHERING' => self::STATUS_VOUCHERING,
        ];

        return array_get( $statuses, $rawStatus );
    }

    /**
     * @return Claim|null
     */
    public function getRecentClaim( ?string $year = null ): ?self
    {
        $claim = $this->recentByYear( $year ?: date( 'Y' ) )
            ->lock(true)
            ->first();
        return $claim ?: null;
    }

    /**
     * @return string
     */
    public function createNextClaimNumber(  ): string
    {
        $options = config( 'eclaims.auto_claim_number', false );

        if ( !$options ) {
            return null;
        }

        /** @var Claim $claim */
        $claim = $this->getRecentClaim();

        return ( new ClaimNumberFactory( $claim, $options ) )->make();
    }

    public function getCheckStatus($status, $query)
    {

        switch ($status) {
            case 'ready':
                    $this->getAllReadyClaims($query);
                break;
            case 'error':
                    $this->getClaimsWithErrors($query);
                break;
            case 'transmitted':
                $this->getAllTransmittedClaims($query);
                break;
            default:
                $this->getStatus($query, $status); 
                break;
        }

        return $query;
    }
}

<?php

namespace App\Model\Entities;

use App\Events\AutoTransmitAddedEvent;
use App\Events\AutoTransmitRemovedEvent;
use App\Jobs\UploadAutoTransmitTransmittalJob;
use App\Model\Concerns\Transmittal\TransmittalScopes;
use App\Model\Factory\TransmittalNumberFactory;
use App\User;
use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Wildside\Userstamps\Userstamps;

/**
 * Class Transmittal.
 *
 * @property string $transmittal_no
 * @property Carbon $transmit_date
 * @property Carbon $transmit_time
 * @property string $transmittal_control_code
 * @property string $receipt_ticket_no
 * @property string $notes
 * @property string $transmit_errors
 * @property bool $auto_transmit
 * @property string $status
 * @property bool $is_valid
 * @property bool $is_mapped
 *
 * @property Collection $claims
 *
 * @package namespace App\Model\Entities;
 */
class Transmittal extends Model implements Transformable
{
    use SoftDeletes,
        TransformableTrait,
        UsesTenantConnection,
        Userstamps,
        TransmittalScopes;

    /**
     * Values used for status field
     */
    const STATUS_UPLOADED = 'uploaded';
    const STATUS_MAPPED = 'mapped';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transmittal_no',
        'transmit_date',
        'transmit_time',
        'transmittal_control_code',
        'receipt_ticket_no',
        'notes',
        'transmit_errors',
        'auto_transmit',
        'status',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'transmit_date',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'auto_transmit' => 'boolean',
    ];

    public function getTransmitTimeAttribute($value) 
    {
        if ($value instanceof Carbon) {
            return $value;
        }
        return $value ? Carbon::createFromFormat('H:i:s', $value) : null;
    }

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        // Disable auto transmit
//        self::saved(function ($model) {
//            /** @var $model self */
//            if ($model->auto_transmit) {
//                event(new AutoTransmitAddedEvent($model));
//            } else {
//                event(new AutoTransmitRemovedEvent($model));
//            }
//        });

        self::deleted(function ($model) {
            /** @var $model self */
            $model->claims->each(function (Claim $claim) {
                $claim->transmittal()->dissociate()->save();
            });
        });
    }


    /**
     * @return HasMany
     */
    public function claims() : HasMany
    {
        return $this->hasMany(Claim::class, 'transmittal_id');
    }

    /**
     * @return self|null
     */
    public function getRecentTransmittal( ?string $year = null ): ?self
    {
        $transmittal = $this->recentByYear( $year ?: date( 'Y' ) )
            ->lock(true)
            ->first();
        return $transmittal ?: null;
    }

    public function createNextTransmittalNumber(): string
    {
        $options = config( 'eclaims.auto_transmittal_number', false );

        if ( !$options ) {
            return null;
        }

        /** @var Claim $claim */
        $claim = $this->getRecentTransmittal();

        return ( new TransmittalNumberFactory( $claim, $options ) )->make();
    }

    /**
     * If transmittal has any errors or any error in claims attached.
     * @return bool
     */
    public function getIsValidAttribute() : bool
    {
        foreach ($this->claims as $claim) {
            /** @var Claim $claim */
            if (!$claim->is_valid) {
                return false;
            }
        }
        return true;
    }

    public function getIsMappedAttribute() : bool
    {
        return $this->status === self::STATUS_MAPPED;
    }

    public function whoCreate()
    {
        $user = User::query()
            ->select('nickname')
            ->where('auth0_id', '=', $this->created_by)
            ->first();

        return $user['nickname'];
    }

    public function whoUpdate()
    {
        $user = User::query()
            ->select('nickname')
            ->where('auth0_id', '=', $this->updated_by)
            ->first();

        return $user['nickname'];
    }
}

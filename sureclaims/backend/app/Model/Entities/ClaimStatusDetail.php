<?php

namespace App\Model\Entities;

use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Wildside\Userstamps\Userstamps;

/**
 * Class ClaimStatusDetail.
 *
 * @property int $id
 * @property int $patient_id
 * @property int $claim_id
 * @property string $date_inquiry
 * @property string $as_of_date
 * @property string $as_of_time
 * @property string $claim_date_refile
 * @property string $claim_date_received
 * @property string $data_json
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Claim $claim
 * @package namespace App\Model\Entities;
 */
class ClaimStatusDetail extends Model implements Transformable
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
        'date_inquiry',
        'as_of_date',
        'as_of_time',
        'claim_date_refile',
        'claim_date_received',
        'data_json'
    ];

    /**
     * @return BelongsTo
     */
    public function claim() : BelongsTo
    {
        return $this->belongsTo(Claim::class, 'claim_id');
    }

}

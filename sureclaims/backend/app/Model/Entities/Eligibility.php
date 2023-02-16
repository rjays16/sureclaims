<?php

/**
 * Eligibility.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Model\Entities;

use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Wildside\Userstamps\Userstamps;

/**
 * Class Eligibility.
 *
 * @package namespace App\Model\Entities;
 *
 * @property int $id
 * @property int $patient_id
 * @property Carbon $checked_at
 * @property bool $is_ok
 * @property string $result_data
 *
 * @property Person $patient
 */
class Eligibility extends Model implements Transformable
{
    use TransformableTrait,
        UsesTenantConnection,
        Userstamps;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'patient_id',
        'checked_at',
        'is_ok',
        'result_data',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'checked_at',
    ];

    /**
     * @return BelongsTo
     */
    public function patient() : BelongsTo
    {
        return $this->belongsTo(Person::class, 'patient_id');
    }

}

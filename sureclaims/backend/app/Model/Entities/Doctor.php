<?php

namespace App\Model\Entities;

use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Wildside\Userstamps\Userstamps;

/**
 * Class Doctor.
 *
 * @package namespace App\Model\Entities;
 *
 * @property int $id
 * @property string $pan
 * @property string $tin
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property string $suffix
 * @property Carbon $birth_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Doctor extends Model implements Transformable
{
    use TransformableTrait,
        Userstamps,
        UsesTenantConnection,
        SoftDeletes;

    protected $dates = [
        'birth_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'pan',
        'tin',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'birth_date',
    ];

}

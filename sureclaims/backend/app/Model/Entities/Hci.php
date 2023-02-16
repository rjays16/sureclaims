<?php

namespace App\Model\Entities;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Hci.
 *
 * @package namespace App\Model\Entities;
 */
class Hci extends Model implements Transformable
{

    use TransformableTrait,
        UsesSystemConnection;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'accreditation_code',
        'pmcc_no',
        'hospital_name'
    ];

}

<?php

namespace App\Model\Entities;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class IcdCode.
 *
 * @package namespace App\Model\Entities;
 */
class IcdCode extends Model implements Transformable
{
    use TransformableTrait,
        UsesSystemConnection;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'description',
    ];

}

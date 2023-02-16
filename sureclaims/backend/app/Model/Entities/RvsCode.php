<?php

namespace App\Model\Entities;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class RvsCode.
 *
 * @package namespace App\Model\Entities;
 */
class RvsCode extends Model implements Transformable
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
        'rvu',
        'with_laterality'
    ];
}

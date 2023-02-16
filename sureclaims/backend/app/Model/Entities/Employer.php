<?php

namespace App\Model\Entities;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Employer.
 *
 * @package namespace App\Model\Entities;
 *
 * @property int $id
 * @property string $pen
 * @property string $name
 * @property string $address
 */
class Employer extends Model implements Transformable
{
    use TransformableTrait,
        UsesSystemConnection;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pen',
        'name',
        'address'
    ];

}

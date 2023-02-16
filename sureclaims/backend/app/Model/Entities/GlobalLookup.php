<?php

namespace App\Model\Entities;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class GlobalLookup.
 *
 * @package namespace App\Model\Entities;
 *
 * @property int $id
 * @property string $domain_name
 * @property string $lookup_type
 * @property string $lookup_value
 */
class GlobalLookup extends Model implements Transformable
{

    use TransformableTrait,
        UsesSystemConnection;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'domain_name',
        'lookup_type',
        'lookup_value',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

}

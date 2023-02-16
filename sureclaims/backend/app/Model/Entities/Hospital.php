<?php

namespace App\Model\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Hospital.
 *
 * @package namespace App\Model\Entities;
 */
class Hospital extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'category',
    ];


    /**
     *
     * @return Relation
     */
    public function user()
    {
        return $this->hasMany(User::class);
    }

}

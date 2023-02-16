<?php

namespace App;

use Hyn\Tenancy\Models\Customer;
use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class User
 *
 * @package App
 *
 * @property int $id
 * @property string $auth0_id
 * @property int $customer_id
 * @property string $name
 * @property string $nickname
 * @property string $email
 * @property string $picture
 * @property string $role
 */
class User extends Authenticatable implements Transformable
{
    use Notifiable, TransformableTrait, UsesSystemConnection;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'auth0_id',
        'name',
        'nickname',
        'email',
        'email_verified',
        'role',
        'user_metadata',
        'app_metadata',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'last_login',
        'last_ip',
    ];

    /**
     * @return BelongsTo
     */
    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

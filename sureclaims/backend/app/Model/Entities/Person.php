<?php

namespace App\Model\Entities;

use App\Model\Concerns\HasFillableRelations;
use App\Model\Concerns\WithMetaphones;
use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Wildside\Userstamps\Userstamps;

/**
 * Class Person.
 *
 * @property int $id
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name_metaphone
 * @property string $first_name_metaphone
 * @property string $middle_name_metaphone
 * @property string $suffix
 * @property Carbon $birth_date
 * @property string $sex
 * @property string $email_address
 * @property string $mailing_address
 * @property string $zip_code
 * @property string $land_line_no
 * @property string $mobile_no
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 *
 * @property Member $member
 * @property Collection $dependents
 */
class Person extends Model implements Transformable
{
    use TransformableTrait,
        Userstamps,
        UsesTenantConnection,
        WithMetaphones,
        SoftDeletes;

    /**
     * @inheritdoc
     */
    protected $table = 'persons';

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'id',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'birth_date',
        'sex',
        'email_address',
        'mailing_address',
        'zip_code',
        'land_line_no',
        'mobile_no',
    ];

    protected $dates = [
        'birth_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @return HasOne
     */
    public function member()
    {
        return $this->hasOne(Member::class);
    }

    /**
     * @return HasMany
     */
    public function dependents() : HasMany
    {
        return $this->hasMany(Member::class, 'principal_id');
    }

    /**
     * @return array
     */
    protected function withMetaphones() : array
    {
        return [
            'first_name',
            'middle_name',
            'last_name',
        ];
    }

    /**
     *
     */
    protected static function boot() {
        parent::boot();

        // Delete related member record
        static::deleting(function (Person $person) {
            $person->member()->delete();
        });
    }

}

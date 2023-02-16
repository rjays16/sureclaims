<?php

namespace App\Model\Entities;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Wildside\Userstamps\Userstamps;

/**
 * Class Member.
 *
 * @package namespace App\Model\Entities;
 *
 * @property int $id
 * @property int $person_id
 * @property int $principal_id
 * @property string $relation
 * @property string $pin
 * @property string $membership_type
 * @property string $pen
 * @property string $employer_name
 *
 * @property Person $person
 * @property Person $principal
 */
class Member extends Model implements Transformable
{
    use SoftDeletes,
        TransformableTrait,
        UsesTenantConnection,
        Userstamps;

    const RELATION_TYPE_MEMBER = 'M';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'person_id',
        'principal_id',
        'relation',
        'pin',
        'membership_type',
        'pen',
        'employer_name',
    ];

    /**
     *
     */
    public static function boot()
    {
        parent::boot();

        // Before save
        self::creating(function ($model) {
            static::resetPrincipalForMember($model);
        });

        // After save
        self::created(function ($model) {
            static::resetDependentsMemberData($model);
        });

        // Before update
        self::updating(function ($model) {
            static::resetPrincipalForMember($model);
        });

        // After update
        self::updated(function ($model) {
            static::resetDependentsMemberData($model);
        });

    }

    /**
     * @return BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * @return BelongsTo
     */
    public function principal(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'principal_id');
    }

    /**
     *
     */
    private static function resetPrincipalForMember(Member $model): void
    {
        if (strtoupper($model->relation) === static::RELATION_TYPE_MEMBER) {
            if ($model->person_id) {
                // The person's self is the principal member
                $model->principal()->associate($model->person);
            } else {
                // No person associated so no principal member as well
                $model->principal()->dissociate();
            }
        } else {
            if ($model->principal_id) {
                // Copy membership data from principal
                if ($model->principal && $model->principal->member) {
                    $principalMember = $model->principal->member;
                    self::copyMemberData($principalMember, $model);
                }
            }
        }
    }

    /**
     *
     */
    private static function resetDependentsMemberData(Member $model): void
    {
        // Only reset if relation = Member (Self) and ssociated with
        // a person record
        if ($model->relation === self::RELATION_TYPE_MEMBER && $model->person) {
            foreach ($model->person->dependents as $dependent) {
                if ($dependent->id === $model->id) {
                    // Avoid circular reference
                    continue;
                }

                self::copyMemberData($model, $dependent);
                $dependent->save();
            }
        }
    }

    /**
     * @param Member $a
     * @param Member $b
     */
    private static function copyMemberData(Member $from, Member $to): void
    {
        $to->pen = $from->pen;
        $to->employer_name = $from->employer_name;
        $to->membership_type = $from->membership_type;
    }
}

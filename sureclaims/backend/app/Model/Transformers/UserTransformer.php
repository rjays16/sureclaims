<?php

namespace App\Model\Transformers;

use App\Model\Entities\UserProfile;
use League\Fractal\TransformerAbstract;
use App\User;

/**
 * Class UserTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'hospital',
    ];

    /**
     * Transform the User entity.
     *
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => (int) $user->id,
            'auth0' => $user->auth0_id,

            /* place your other model properties here */
            'name' => $user->name,
            'nickname' => $user->nickname,
            'picture' => $user->picture,
            'email' => strtolower($user->email),
            'role' => $user->role,

            'createdAt' => $user->created_at,
            'updatedAt' => $user->updated_at
        ];
    }

}

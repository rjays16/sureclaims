<?php

namespace App\Model\Transformers;

use League\Fractal\TransformerAbstract;
use App\Model\Entities\UserProfile;

/**
 * Class UserProfileTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class UserProfileTransformer extends TransformerAbstract
{
    /**
     * Transform the UserProfile entity.
     *
     * @param \App\Model\Entities\UserProfile $model
     *
     * @return array
     */
    public function transform(UserProfile $model)
    {
        return [
            'id' => (int) $model->id,

            /* place your other model properties here */
            'lastName' => $model->last_name,
            'firstName' => $model->first_name,
            'middleName' => $model->middle_name,
            'contactNo' => $model->contact_no,
            'address' => $model->address,
            'country' => $model->country,
            'birthDate' => $model->birth_date,

            'createdAt' => $model->created_at,
            'updatedAt' => $model->updated_at
        ];
    }
}

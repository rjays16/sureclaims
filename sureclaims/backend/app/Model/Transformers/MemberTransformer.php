<?php

namespace App\Model\Transformers;

use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use App\Model\Entities\Member;

/**
 * Class MemberTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class MemberTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'person',
        'principal',
    ];

    /**
     * Transform the Member entity.
     *
     * @param \App\Model\Entities\Member $model
     *
     * @return array
     */
    public function transform(Member $model) : array
    {
        return [
            'id' => (int) $model->id,

            /* place your other model properties here */
            'principalId' => $model->principal_id,
            'relation' => (string) $model->relation,
            'pin' => (string) $model->pin,
            'membershipType' => (string) $model->membership_type,
            'pen' => (string) $model->pen,
            'employerName' => (string) $model->employer_name,

//            'createdAt' => $model->created_at,
//            'updatedAt' => $model->updated_at
        ];
    }

    /**
     * @param Member $member
     *
     * @return Item
     */
    public function includePerson(Member $member)
    {
        if (!$member->person) {
            return null;
        }
        return $this->item($member->person, new PersonTransformer());
    }

    /**
     * @param Member $member
     *
     * @return Item
     */
    public function includePrincipal(Member $member)
    {
        if (!$member->principal) {
            return null;
        }
        return $this->item($member->principal, new PersonTransformer());
    }
}

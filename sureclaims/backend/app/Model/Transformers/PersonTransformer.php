<?php

namespace App\Model\Transformers;

use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use App\Model\Entities\Person;

/**
 * Class PersonTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class PersonTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'member',
    ];

    /**
     * Transform the Person entity.
     *
     * @param \App\Model\Entities\Person $model
     *
     * @return array
     */
    public function transform(Person $model)
    {
        return [
            'id' => (int) $model->id,

            /* place your other model properties here */
            'lastName' => (string) $model->last_name,
            'firstName' => (string) $model->first_name,
            'middleName' => (string) $model->middle_name,
            'suffix' => (string) $model->suffix,
            'birthDate' => \ec_to_date($model->birth_date),
            'sex' => (string) $model->sex,
            'emailAddress' => (string) $model->email_address,
            'mailingAddress' => (string) $model->mailing_address,
            'zipCode' => (string) $model->zip_code,
            'landLineNo' => (string) $model->land_line_no,
            'mobileNo' => (string) $model->mobile_no,

            'createdAt' => \ec_to_datetime($model->created_at),
            'updatedAt' => \ec_to_datetime($model->updated_at),
        ];
    }

    /**
     * @param Person $person
     *
     * @return Item
     */
    public function includeMember(Person $person)
    {
        if (!$person->member) {
            return null;
        }
        return $this->item($person->member, new MemberTransformer());
    }
}

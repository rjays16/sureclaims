<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

class HospitalType extends BaseType
{
    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'Hospital',
    ];

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'System generated ID of the company',
            ],
            'code' => [
                'type' => Type::string(),
            ],
            'name' => [
                'type' => Type::string(),
            ],
            'address' => [
                'type' => Type::string(),
            ],
            'category' => [
                'type' => Type::string(),
            ],
        ];
    }
}

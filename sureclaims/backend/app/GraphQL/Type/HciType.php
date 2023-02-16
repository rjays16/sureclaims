<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

class HciType extends BaseType
{
    protected $attributes = [
        'name' => 'Hci',
        'description' => 'A type'
    ];

    public function fields()
    {
        return [
            'id' => [ 'type' => Type::id() ],
            'accreditationCode' => [ 'type' => Type::string() ],
            'pmccNo'=> [ 'type' => Type::string() ],
            'hospitalName' => [ 'type' => Type::string() ],
            'createdAt' => [ 'type' => Type::string() ],
            'updatedAt' => [ 'type' => Type::string() ],
        ];
    }
}

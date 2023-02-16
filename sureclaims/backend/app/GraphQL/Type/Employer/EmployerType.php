<?php

namespace App\GraphQL\Type\Employer;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

class EmployerType extends BaseType
{
    protected $attributes = [
        'name' => 'Employer',
        'description' => 'A type'
    ];

    public function fields()
    {
        return [
            'id' => [ 'type' => Type::id() ],
            'pen' => [ 'type' => Type::string() ],
            'name' => [ 'type' => Type::string() ],
            'address' => [ 'type' => Type::string() ]
        ];
    }
}

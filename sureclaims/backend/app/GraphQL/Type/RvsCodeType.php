<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

class RvsCodeType extends BaseType
{
    protected $attributes = [
            'name'        => 'RvsCode',
            'description' => 'RVS',
        ];

    public function fields()
    {
        return [
            'id'              => ['type' => Type::id()],
            'code'            => ['type' => Type::string()],
            'description'     => ['type' => Type::string()],
            'rvu'             => ['type' => Type::string()],
            'with_laterality' => ['type' => Type::boolean()],
            'createdAt'       => ['type' => Type::string()],
            'updatedAt'       => ['type' => Type::string()],

        ];
    }
}

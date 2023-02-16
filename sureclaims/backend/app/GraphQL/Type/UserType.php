<?php

/**
 * UserType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type;

use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of UserType
 *
 */

class UserType extends BaseType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A registered user account',
    ];

    /*
    * Uncomment following line to make the type input object.
    * http://graphql.org/learn/schema/#input-types
    */
    // protected $inputObject = true;

    public function fields()
    {
        return [
            'id' => ['type' => Type::nonNull(Type::id())],
            'auth0' => ['type' => Type::string()],
            'name' => ['type' => Type::string()],
            'nickname' => ['type' => Type::string()],
            'email' => [
                'type' => Type::string(),
                'resolve' => function ($root) {
                    return strtolower($root['email']);
                }
            ],
        ];
    }
}

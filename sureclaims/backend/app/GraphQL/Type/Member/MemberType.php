<?php

/**
 * MemberType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Member;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 *
 * Description of MemberType
 *
 */

class MemberType extends BaseType
{
    protected $attributes = [
        'name' => 'Member',
    ];

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id' => [ 'type' => Type::nonNull(Type::id()) ],
            'pin' => [ 'type' => Type::string() ],
            'membershipType' => [ 'type' => Type::string() ],
            'pen' => [ 'type' => Type::string() ],
            'employerName' => [ 'type' => Type::string() ],

            'principalId' => [ 'type' => Type::string() ],
            'relation' => [ 'type' => Type::string() ],

            'principal' => [ 'type' => GraphQL::type('Person') ],

            'createdAt' => [ 'type' => Type::string() ],
            'updatedAt' => [ 'type' => Type::string() ],
        ];
    }
}

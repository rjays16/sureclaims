<?php

/**
 * MemberInputType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Member;

use GraphQL\Type\Definition\Type;
use GraphQL;

/**
 *
 * Description of MemberInputType
 *
 */

class MemberInputType extends MemberType
{
    /** @var array  */
    protected $attributes = [
        'name' => 'MemberInput',
    ];

    /** @var bool */
    protected $inputObject = true;

    public function fields()
    {
        return [
            'pin' => [ 'type' => Type::string() ],
            'membershipType' => [ 'type' => Type::string() ],
            'pen' => [ 'type' => Type::string() ],
            'employerName' => [ 'type' => Type::string() ],
            'principalId' => [ 'type' => Type::int() ],
            'relation' => [ 'type' => Type::string() ],
        ];
    }
}

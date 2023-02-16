<?php

/**
 * MembersPageType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Member;

use App\GraphQL\Type\BasePageType;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 *
 * Description of MembersPageType
 *
 */
class MembersPageType extends BasePageType
{
    protected $attributes = [
        'name' => 'MembersPage',
    ];

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'members' => [ 'type' => Type::listOf(GraphQL::type('Person')) ],
        ] + parent::fields();
    }

}

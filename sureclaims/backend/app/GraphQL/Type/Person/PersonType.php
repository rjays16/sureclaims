<?php

/**
 * PersonType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Person;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 *
 * Description of PersonType
 *
 */

class PersonType extends BaseType
{
    protected $attributes = [
        'name' => 'Person',
        'description' => 'A person registered on the company records'
    ];

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id' => ['type' => Type::nonNull(Type::id())],
            'lastName' => [ 'type' => Type::string() ],
            'firstName' => [ 'type' => Type::string() ],
            'middleName' => [ 'type' => Type::string() ],
            'suffix' => [ 'type' => Type::string() ],
            'birthDate' => [ 'type' => Type::string() ],
            'sex' => [ 'type' => Type::string() ],
            'emailAddress' => [ 'type' => Type::string() ],
            'mailingAddress' => [ 'type' => Type::string() ],
            'zipCode' => [ 'type' => Type::string() ],
            'landLineNo' => [ 'type' => Type::string() ],
            'mobileNo' => [ 'type' => Type::string() ],
            'createdAt' => [ 'type' => Type::string() ],
            'updatedAt' => [ 'type' => Type::string() ],

//            'fullname' => [
//                'type' => Type::string(),
//                'resolve' => function ($root) {
//                    $parts = [];
//                    $parts[] = @$root['lastName'] . ',';
//                    $parts[] = @$root['firstName'];
//
//                    $middleName = @$root['middleName'];
//                    if ($middleName) {
//                        $parts[] = substr($middleName, 0, 1) . '.';
//                    }
//                    return implode(' ', $parts);
//                }
//            ],

            //
            'member' => [ 'type' => GraphQL::type('Member') ],
        ];
    }

}

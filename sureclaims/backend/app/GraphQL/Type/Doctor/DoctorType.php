<?php

/**
 * DoctorType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Doctor;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 *
 * Description of DoctorType
 *
 */

class DoctorType extends BaseType
{
    protected $attributes = [
        'name' => 'Doctor',
        'description' => 'A registered health care expert'
    ];

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id' => ['type' => Type::nonNull(Type::id())],
            'pan' => [ 'type' => Type::string() ],
            'tin' => [ 'type' => Type::string() ],
            'lastName' => [ 'type' => Type::string() ],
            'firstName' => [ 'type' => Type::string() ],
            'middleName' => [ 'type' => Type::string() ],
            'suffix' => [ 'type' => Type::string() ],
            'birthDate' => [ 'type' => Type::string() ],
            'createdAt' => [ 'type' => Type::string() ],
            'updatedAt' => [ 'type' => Type::string() ],
        ];
    }

}

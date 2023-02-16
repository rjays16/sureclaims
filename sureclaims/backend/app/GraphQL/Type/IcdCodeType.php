<?php

/**
 * IcdCodeType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 * Class IcdCodeType
 *
 * @package App\GraphQL\Type
 */
class IcdCodeType extends BaseType
{
    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'IcdCode',
        'description' => 'ICD-10 coding for a specific diagnosis'
    ];

    public function fields()
    {
        return [
            'id' => [ 'type' => Type::id() ],
            'code' => [ 'type' => Type::string() ],
            'description' => [ 'type' => Type::string() ],
            'createdAt' => [ 'type' => Type::string() ],
            'updatedAt' => [ 'type' => Type::string() ],
        ];
    }
}

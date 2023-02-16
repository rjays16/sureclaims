<?php

/**
 * LookupType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;

/**
 * Class LookupType
 *
 * @package App\GraphQL\Type
 */
class LookupType extends BaseType
{
    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'LookupType',
        'description' => 'A type'
    ];

    /**
     * @return array
     */
    public function fields() : array
    {
        return [
            'id' => [ 'type' => Type::id() ],
            'domain' => [ 'type' => Type::string() ],
            'type' => [ 'type' => Type::string() ],
            'value' => [ 'type' => Type::string() ],
        ];
    }
}

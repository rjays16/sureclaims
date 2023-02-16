<?php

/**
 * PageInfoType.php
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
 *
 * Description of PageInfoType
 *
 */

class PageInfoType extends BaseType
{
    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'PageInfo',
    ];

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'pageSize' => [ 'type' => Type::int() ],
            'currentPage' => [ 'type' => Type::int() ],
            'lastPage' => [ 'type' => Type::int() ],
            'total' => [ 'type' => Type::int() ],
            'hasMorePages' => [ 'type' => Type::boolean() ],
        ];
    }
}

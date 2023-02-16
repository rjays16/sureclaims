<?php

/**
 * BasePageType.php
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
 * Description of BasePageType
 *
 */

abstract class BasePageType extends BaseType
{

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'pageInfo' => [ 'type' => GraphQL::type('PageInfo') ],
        ];
    }
}

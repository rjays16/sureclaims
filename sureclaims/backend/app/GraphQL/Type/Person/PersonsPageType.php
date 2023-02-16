<?php

/**
 * PersonsPageType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Person;

use App\GraphQL\Type\BasePageType;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 *
 * Description of PersonsPageType
 *
 */
class PersonsPageType extends BasePageType
{
    protected $attributes = [
        'name' => 'PersonsPage',
    ];

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'persons' => [ 'type' => Type::listOf(GraphQL::type('Person')) ],
        ] + parent::fields();
    }

}

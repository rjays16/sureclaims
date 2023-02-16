<?php

/**
 * DoctorsPageType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Doctor;

use App\GraphQL\Type\BasePageType;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 *
 * Description of DoctorsPageType
 *
 */
class DoctorsPageType extends BasePageType
{
    protected $attributes = [
        'name' => 'DoctorsPage',
    ];

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'doctors' => [ 'type' => Type::listOf(GraphQL::type('Doctor')) ],
        ] + parent::fields();
    }

}

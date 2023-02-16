<?php

/**
 * TransmittalsPageType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Transmittal;

use App\GraphQL\Type\BasePageType;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 *
 * Description of TransmittalsPageType
 *
 */
class TransmittalsPageType extends BasePageType
{
    protected $attributes = [
        'name' => 'TransmittalsPage',
    ];

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'transmittals' => [ 'type' => Type::listOf(GraphQL::type('Transmittal')) ],
        ] + parent::fields();
    }

}

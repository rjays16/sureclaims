<?php

/**
 * ClaimsPageType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Claim;

use App\GraphQL\Type\BasePageType;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 *
 * Description of ClaimsPageType
 *
 */
class ClaimsPageType extends BasePageType
{
    protected $attributes = [
        'name' => 'ClaimsPage',
    ];

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'claims' => [ 'type' => Type::listOf(GraphQL::type('Claim')) ],
        ] + parent::fields();
    }

}

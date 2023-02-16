<?php

/**
 * RequiredDocumentType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Eligibility;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 *
 * Description of RequiredDocumentType
 *
 */
class RequiredDocumentType extends BaseType
{
    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'RequiredDocument',
        'description' => 'A type'
    ];

    /**
     * @return array
     */
    public function fields() : array
    {
        return [
            'code' => [ 'type' => Type::string() ],
            'name' => [ 'type' => Type::string() ],
            'reason' => [ 'type' => Type::string() ],
        ];
    }
}

<?php

/**
 * TransmittalType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Transmittal;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 *
 * Description of TransmittalType
 *
 */

class TransmittalType extends BaseType
{
    protected $attributes = [
        'name' => 'Transmittal',
    ];


    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id' => [ 'type' => Type::nonNull(Type::id()) ],

            'transmittalNo' => [ 'type' => Type::string() ],
            'transmittalControlCode' => [ 'type' => Type::string() ],
            'transmitDate' => [ 'type' => Type::string() ],
            'transmitTime' => [ 'type' => Type::string() ],
            'receiptTicketNo' => [ 'type' => Type::string() ],
            'notes' => [ 'type' => Type::string() ],
            'transmitErrors' => [ 'type' => Type::string() ],
            'autoTransmit' => [ 'type' => Type::boolean() ],
            'status' => [ 'type' => Type::string() ],
            'isValid' => [ 'type' => Type::nonNull(Type::boolean()) ],

            'createdAt' => [ 'type' => Type::string() ],
            'updatedAt' => [ 'type' => Type::string() ],

            'createdBy' => [ 'type' => Type::string() ],
            'updatedBy' => [ 'type' => Type::string() ],

            // Relations
            'claims' => [ 'type' => Type::listOf(GraphQL::type('Claim')) ],
        ];
    }
}

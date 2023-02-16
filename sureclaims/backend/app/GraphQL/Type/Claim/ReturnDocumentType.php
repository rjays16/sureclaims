<?php

namespace App\GraphQL\Type\Claim;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 * Class ReturnDocumentType
 *
 * @package App\GraphQL\Type\Claim
 */
class ReturnDocumentType extends BaseType
{
    protected $attributes = [
        'name' => 'ReturnDocument',
        'description' => 'A type'
    ];

    public function fields()
    {
        return [
            'id' => [ 'type' => Type::nonNull(Type::ID()) ],
            'claimId' => [ 'type' => Type::string() ],
            'patientId' => [ 'type' => Type::string() ],
            'supportDocumentId' => [ 'type' => Type::string() ],
            'isUploaded' => [ 'type' => Type::nonNull(Type::boolean()) ],
            'createdAt' => [ 'type' => Type::string() ],
            'updatedAt' => [ 'type' => Type::string() ],

            // Relation
            'supportingDocument' => [ 'type' => GraphQL::type('SupportingDocument') ],
        ];
    }
}

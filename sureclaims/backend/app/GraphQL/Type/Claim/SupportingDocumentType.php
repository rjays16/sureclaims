<?php

/**
 * SupportingDocumentType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Claim;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 * Class SupportingDocumentType
 *
 * @package App\GraphQL\Type\Claim
 */
class SupportingDocumentType extends BaseType
{
    protected $attributes = [
        'name' => 'SupportingDocument',
        'description' => 'Document attached to a claim to provide supplementary information'
    ];

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id' => [ 'type' => Type::nonNull(Type::ID()) ],
            'claimId' => [ 'type' => Type::string() ],
            'patientId' => [ 'type' => Type::string() ],
            'storageUid' => [ 'type' => Type::string() ],
            'documentType' => [ 'type' => Type::string() ],
            'publicPath' => [ 'type' => Type::string() ],
            'fileName' => [ 'type' => Type::string() ],
            'fileSize' => [ 'type' => Type::int() ],
            'hash' => [ 'type' => Type::string() ],
            'encryptedHash' => [ 'type' => Type::string() ],
            'createdAt' => [ 'type' => Type::string() ],
            'updatedAt' => [ 'type' => Type::string() ],
        ];
    }
}

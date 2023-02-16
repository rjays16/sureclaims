<?php

/**
 * SupportingDocumentInputType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Claim;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**Ui * Class SupportingDocumentInputType
 *
 * @package App\GraphQL\Type\Claim
 */
class SupportingDocumentInputType extends BaseType
{
    protected $attributes = [
        'name' => 'SupportingDocumentInput',
        'description' => 'Document to be retrieved from the filesystem'
    ];

    protected $inputObject = true;

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id' => [ 'type' => Type::ID() ],
            'claimId' => [ 'type' => Type::string() ],
            'patientId' => [ 'type' => Type::string() ],
            'documentType' => [ 'type' => Type::string() ],
            'fileName' => [ 'type' => Type::string() ],
        ];
    }
}

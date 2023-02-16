<?php

namespace App\GraphQL\Type\Claim;

use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL\Type\Definition\Type;

/**Ui * Class ReturnDocumentInputType
 *
 * @package App\GraphQL\Type\Claim
 */
class ReturnDocumentInputType extends BaseType
{
    protected $attributes = [
        'name' => 'ReturnDocumentInput',
        'description' => 'Document to be retrieved from the filesystem'
    ];

    protected $inputObject = true;

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id' => ['type' => Type::ID()],
            'claimId' => ['type' => Type::string()],
            'patientId' => ['type' => Type::string()],
            'documentType' => ['type' => Type::string()],
            'fileName' => ['type' => Type::string()],
        ];
    }
}

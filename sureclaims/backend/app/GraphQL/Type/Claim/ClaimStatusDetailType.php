<?php

namespace App\GraphQL\Type\Claim;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

class ClaimStatusDetailType extends BaseType
{
    protected $attributes = [
        'name' => 'ClaimStatusDetail',
        'description' => 'Claim status detail'
    ];

    public function fields()
    {
        return [
            'id' => [ 'type' => Type::nonNull(Type::ID()) ],
            'patientId' => [ 'type' => Type::string() ],
            'claimId' => [ 'type' => Type::string() ],
            'dateInquiry' => [ 'type' => Type::string() ],
            'asOfDate' => [ 'type' => Type::string() ],
            'asOfTime' => [ 'type' => Type::string() ],
            'claimDateRefile' => [ 'type' => Type::string() ],
            'claimDateReceived' => [ 'type' => Type::string() ],

            'createdAt' => [ 'type' => Type::string() ],
            'updatedAt' => [ 'type' => Type::string() ],
        ];
    }
}

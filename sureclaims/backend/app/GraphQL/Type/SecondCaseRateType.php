<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

class SecondCaseRateType extends BaseType
{
    protected $attributes = [
        'name' => 'SecondCaseRate',
        'description' => 'A type'
    ];

    public function fields()
    {
        return [
            'id' => [ 'type' => Type::int() ],
            'code' => [ 'type' => Type::string() ],
            'description' => [ 'type' => Type::string() ],
            'itemCode' => [ 'type' => Type::string() ],
            'itemDescription' => [ 'type' => Type::string() ],
            'hciFee' => [ 'type' => Type::float() ],
            'profFee' => [ 'type' => Type::float() ],
            'amount' => [
                'type' => Type::float(),
                'description' => 'Total amount of primary fees: hci_fee + prof_fee'
            ],
            'effectivityDate' => [ 'type' => Type::string() ],
            'caseType' => [ 'type' => Type::string() ],
            'isIcd' => [ 'type' => Type::boolean() ],
            'isRvs' => [ 'type' => Type::boolean() ],
            'createdAt' => [ 'type' => Type::string() ],
            'updatedAt' => [ 'type' => Type::string() ],
        ];
    }
}

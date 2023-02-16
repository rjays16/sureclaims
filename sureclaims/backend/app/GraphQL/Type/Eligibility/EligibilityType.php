<?php

/**
 * EligibilityType.php
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
 * Description of EligibilityType
 *
 */

class EligibilityType extends BaseType
{
    protected $attributes = [
        'name' => 'Eligibility',
    ];


    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id' => [ 'type' => Type::nonNull(Type::id()) ],

            'isOk' => [ 'type' => Type::boolean() ],
            'trackingNumber' => [ 'type' => Type::string() ],
            'isNHTS' => [ 'type' => Type::string() ],
            'with3Over6' => [ 'type' => Type::string() ],
            'with9Over12' => [ 'type' => Type::string() ],
            'remainingDays' => [ 'type' => Type::string() ],
            'asOf' => [ 'type' => Type::string() ],

            'patientIs' => [ 'type' => Type::string() ],
            'patientLastName' => [ 'type' => Type::string() ],
            'patientFirstName' => [ 'type' => Type::string() ],
            'patientMiddleName' => [ 'type' => Type::string() ],
            'patientSuffix' => [ 'type' => Type::string() ],
            'patientBirthDate' => [ 'type' => Type::string() ],

            'admitted' => [ 'type' => Type::string() ],
            'discharged' => [ 'type' => Type::string() ],

            'pin' => [ 'type' => Type::string() ],
            'memberType' => [ 'type' => Type::string() ],
            'memberLastName' => [ 'type' => Type::string() ],
            'memberFirstName' => [ 'type' => Type::string() ],
            'memberMiddleName' => [ 'type' => Type::string() ],
            'memberSuffix' => [ 'type' => Type::string() ],
            'memberBirthDate' => [ 'type' => Type::string() ],
            'memberCategoryId' => [ 'type' => Type::string() ],
            'memberCategoryDesc' => [ 'type' => Type::string() ],

            'pen' => [ 'type' => Type::string() ],
            'url' => [ 'type' => Type::string() ],

            'employerName' => [ 'type' => Type::string() ],

            'requiredDocuments' => [ 'type' => Type::listOf(GraphQL::type('RequiredDocument')) ],

            'createdAt' => [ 'type' => Type::string() ],
            'updatedAt' => [ 'type' => Type::string() ],
        ];
    }
}

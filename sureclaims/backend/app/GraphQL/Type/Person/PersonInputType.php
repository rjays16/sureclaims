<?php

/**
 * PersonInputType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type\Person;

use GraphQL;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of PersonInputType
 *
 */

class PersonInputType
    extends PersonType
    implements InputType
{
    /** @var array  */
    protected $attributes = [
        'name' => 'PersonInput',
    ];

    /** @var bool */
    protected $inputObject = true;

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'lastName' => [ 'type' => Type::string() ],
            'firstName' => [ 'type' => Type::string() ],
            'middleName' => [ 'type' => Type::string() ],
            'suffix' => [ 'type' => Type::string() ],
            'birthDate' => [ 'type' => Type::string() ],
            'sex' => [ 'type' => Type::string() ],
            'emailAddress' => [ 'type' => Type::string() ],
            'mailingAddress' => [ 'type' => Type::string() ],
            'zipCode' => [ 'type' => Type::string() ],
            'landLineNo' => [ 'type' => Type::string() ],
            'mobileNo' => [ 'type' => Type::string() ],
        ];
    }
}

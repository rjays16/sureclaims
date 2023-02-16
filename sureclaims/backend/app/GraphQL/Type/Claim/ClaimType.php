<?php

/**
 * ClaimType.php
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
 *
 * Description of ClaimType
 *
 */
class ClaimType extends BaseType
{
    protected $attributes = [
        'name' => 'Claim',
    ];


    /**
     * @return array
     */
    public function fields()
    {
        return [
            'id'            => ['type' => Type::nonNull(Type::id())],
            'claimNumber'   => ['type' => Type::string()],
            'transmittalId' => ['type' => Type::string()],

            'patientId'    => ['type' => Type::id()],
            'lhioSeriesNo' => ['type' => Type::string()],

            'admissionDate' => ['type' => Type::string()],
            'dischargeDate' => ['type' => Type::string()],

            'status'           => ['type' => Type::string()],
            'data'             => ['type' => Type::string()],
            'xml'              => ['type' => Type::string()],
            'xmlLink'          => ['type' => Type::string()],
            'isValid'          => ['type' => Type::nonNull(Type::boolean())],
            'validationErrors' => ['type' => Type::string()],

            'createdAt'           => ['type' => Type::string()],
            'updatedAt'           => ['type' => Type::string()],

            // Relations
            'patient'             => ['type' => GraphQL::type('Person')],
            'transmittal'         => ['type' => GraphQL::type('Transmittal')],
            'supportingDocuments' => ['type' => Type::listOf(GraphQL::type('SupportingDocument'))],
            'returnDocuments'     => ['type' => Type::listOf(GraphQL::type('ReturnDocument'))],
            'claimStatusDetail'   => ['type' => GraphQL::type('ClaimStatusDetail')],
        ];
    }
}

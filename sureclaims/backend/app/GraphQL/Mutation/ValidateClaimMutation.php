<?php

/**
 * ValidateClaimnMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\Exceptions\InvalidXMLException;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of ValidateClaimnMutation
 *
 */
class ValidateClaimMutation extends Mutation
{
    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'validateClaim'
    ];

    /** @var EclaimsServiceInterface */
    private $eclaimsService;

    /**
     * ValidateClaimMutation constructor.
     *
     * @param array $attributes
     * @param EclaimsServiceInterface $eclaimsService
     */
    public function __construct($attributes = [], EclaimsServiceInterface $eclaimsService)
    {
        $this->eclaimsService = $eclaimsService;
        parent::__construct($attributes);
    }

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return Type::listOf(Type::string());
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'data' => ['name' => 'data', 'type' => Type::string()],
        ];
    }


    /**
     * @param $root
     * @param array $args
     *
     * @return mixed
     */
    public function resolve($root, $args)
    {
        $errors = [];
        try {

            $transformer = new ClaimXmlTransformer();
            $xmlData = $transformer->transform($model);

            $service->validateClaimXML($model->data_xml);
        } catch (InvalidXMLException $e) {
            $model->validation_errors = \GuzzleHttp\json_encode($e->validationErrors());
        }
        return $result;
    }
}

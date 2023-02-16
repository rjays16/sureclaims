<?php

/**
 * VerifyPinMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\Lib\Soap\EclaimsServiceInterface;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Carbon;

/**
 *
 * Description of VerifyPinMutation
 *
 */
class VerifyPinMutation extends Mutation
{
    /** @var EclaimsServiceInterface */
    private $eclaimsService;

    /**
     * VerifyPinMutation constructor.
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
     * @var array
     */
    protected $attributes = [
        'name' => 'verifyPin'
    ];

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return Type::string();
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'lastName' => [
                'name' => 'lastName', 
                'type' => Type::string(),
                'rules' => [ 'required' ]
            ],
            'firstName' => [
                'name' => 'firstName', 
                'type' => Type::string(),
                'rules' => [ 'required' ]
            ],
            'middleName' => ['name' => 'middleName', 'type' => Type::string()],
            'suffix' => ['name' => 'suffix', 'type' => Type::string()],
            'birthDate' => [
                'name' => 'birthDate', 
                'type' => Type::string(),
                'rules' => [ 'required' ]
            ],
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
        $result = call_user_func_array([$this->eclaimsService, 'getMemberPin'], [
            'pMemberLastName' => $args['lastName'],
            'pMemberFirstName' => $args['firstName'],
            'pMemberMiddleName' => $args['middleName'],
            'pMemberSuffix' => $args['suffix'],
            'pMemberBirthDate' => $args['birthDate'],
        ]);

        return $result;
    }
}

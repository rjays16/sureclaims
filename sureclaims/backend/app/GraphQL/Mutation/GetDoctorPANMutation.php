<?php

/**
 * GetDoctorPANMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\PhilHealthEClaimsEncryptor;
use App\Model\Repositories\Contracts\EligibilityRepository;
use App\Model\Repositories\Contracts\PersonRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Carbon;

/**
 *
 * Description of GetDoctorPANMutation
 *
 */
class GetDoctorPANMutation extends Mutation
{
    /** @var EclaimsServiceInterface */
    private $eclaimsService;

    /**
     * GetDoctorPANMutation constructor.
     *
     * @param array $attributes
     * @param EclaimsServiceInterface $eclaimsService
     */
    public function __construct(
        $attributes = [],
        EclaimsServiceInterface $eclaimsService
    ) {
        $this->eclaimsService = $eclaimsService;
        parent::__construct($attributes);
    }

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'getDoctorPAN'
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
            'tin' => ['name' => 'tin', 'type' => Type::string()],
            'lastName' => ['name' => 'lastName', 'type' => Type::string()],
            'firstName' => ['name' => 'firstName', 'type' => Type::string()],
            'middleName' => ['name' => 'middleName', 'type' => Type::string()],
            'suffix' => ['name' => 'suffix', 'type' => Type::string()],
            'birthDate' => ['name' => 'birthDate', 'type' => Type::string()],
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
        $parameters = [
            'pDoctorTIN' => (string) @$args['tin'],
            'pDoctorLastName' => (string) strtoupper(@$args['lastName']),
            'pDoctorFirstName' => (string) strtoupper(@$args['firstName']),
            'pDoctorMiddleName' => (string) strtoupper(@$args['middleName']),
            'pDoctorSuffix' => (string) strtoupper(@$args['suffix']),
            'pDoctorBirthDate' => '00-00-0000',
        ];
        if (isset($args['birthDate'])) {
            $parameters['pDoctorBirthDate'] = $args['birthDate'];
        }

        $result = call_user_func_array([$this->eclaimsService, 'getDoctorPAN'], [
            'parameters' => $parameters
        ]);

        if ($result === '') {
            throw new \Exception('No matching record found');
        }

        if (preg_match("/^ERROR\: \<(.*)\>$/", $result, $matches)) {
            throw new \Exception($matches[1]);
        }

        return $result;
    }
}

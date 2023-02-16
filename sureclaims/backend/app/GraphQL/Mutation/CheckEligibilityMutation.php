<?php

/**
 * CheckEligibilityMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\Exceptions\PhilhealthException;
use App\Lib\Soap\PhilHealthEClaimsEncryptor;
use App\Model\Repositories\Contracts\EligibilityRepository;
use App\Model\Repositories\Contracts\PersonRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Carbon;

/**
 *
 * Description of CheckEligibilityMutation
 *
 */
class CheckEligibilityMutation extends Mutation
{
    /** @var PersonRepository */
    private $persons;

    /** @var EligibilityRepository  */
    private $eligibilities;

    /** @var EclaimsServiceInterface */
    private $eclaimsService;

    /**
     * CheckEligibilityMutation constructor.
     *
     * @param array $attributes
     * @param EclaimsServiceInterface $eclaimsService
     */
    public function __construct(
        $attributes = [],
        EclaimsServiceInterface $eclaimsService,
        PersonRepository $persons,
        EligibilityRepository $eligibilities
    ) {
        $this->eclaimsService = $eclaimsService;
        $this->persons = $persons;
        $this->eligibilities = $eligibilities;
        parent::__construct($attributes);
    }

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'checkEligibility'
    ];

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type('Eligibility');
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'patient' => ['name' => 'patient', 'type' => Type::nonNull(Type::string())],
            'confinementDates' => [
                'name' => 'confinementDates',
                'type' => Type::listOf(Type::string())
            ],
            'rvs' => ['name' => 'rvs', 'type' => Type::string()],
            'actualAmount' => ['name' => 'actualAmount', 'type' => Type::nonNull(Type::float())],
            'claimAmount' => ['name' => 'claimAmount', 'type' => Type::nonNull(Type::float())],
            'isFinal' => ['name' => 'isFinal', 'type' => Type::nonNull(Type::boolean())],
        ];
    }


    /**
     * @param $root
     * @param array $args
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function resolve($root, $args)
    {
        $this->persons->resetScope();
        $this->persons->parsePresenterIncludes([
            'member',
            'member.principal.member'
        ]);
        $person = $this->persons->find(@$args['patient']);

        $admitted = array_get($args, 'confinementDates.0');
        $discharged = array_get($args, 'confinementDates.1', null);

        $parameters = [
            'pPIN' => array_get($person, 'member.principal.member.pin'),
            'pMemberLastName' => strtoupper(trim(array_get($person, 'member.principal.lastName'))),
            'pMemberFirstName' => strtoupper(trim(array_get($person, 'member.principal.firstName'))),
            'pMemberMiddleName' => strtoupper(trim(array_get($person, 'member.principal.middleName', ''))),
            'pMemberSuffix' => strtoupper(trim(array_get($person, 'member.principal.suffix'))),
            'pMemberBirthDate' => array_get($person, 'member.principal.birthDate'),
            'pMailingAddress' => array_get($person, 'member.principal.mailingAddress'),
            'pZipCode' => array_get($person, 'member.principal.zipCode'),
            'pPatientIs' => strtoupper(trim(array_get($person, 'member.relation'))),
            'pAdmissionDate' => $admitted,
            'pDischargeDate' => $discharged,
            'pPatientLastName' => strtoupper(trim(array_get($person, 'lastName'))),
            'pPatientFirstName' => strtoupper(trim(array_get($person, 'firstName'))),
            'pPatientMiddleName' => strtoupper(trim(array_get($person, 'middleName', ''))),
            'pPatientSuffix' => strtoupper(trim(array_get($person, 'suffix'))),
            'pPatientBirthDate' => array_get($person, 'birthDate'),
            'pPatientGender' => strtoupper(trim(array_get($person, 'sex'))),
            'pMemberShipType' => strtoupper(trim(array_get($person, 'member.membershipType'))),
            'pPEN' => array_get($person, 'member.pen'),
            'pEmployerName' => array_get($person, 'member.employerName'),
            'pRVS' => @$args['rvs'],
            'pTotalAmountActual' => @$args['actualAmount'],
            'pTotalAmountClaimed' => @$args['claimAmount'],
            'pIsFinal' => @$args['isFinal'] ? 1 : 0,
        ];

        try {
            $result = call_user_func_array([$this->eclaimsService, 'isClaimEligible'], [
                'parameters' => $parameters
            ]);

            $result = array_get($result, 'RESPONSE', []);

            $trackingNo = array_get($result, '@attributes.TRACKING_NUMBER', false);
            $result = $this->eligibilities->create([
                'patient_id' => $args['patient'],
                'checked_at' => Carbon::now()->toDateTimeString(),
                'is_ok' => !empty($trackingNo) && array_get($result, '@attributes.ISOK') === 'YES',
                'result_data' => json_encode($result)
            ]);
        } catch (PhilhealthException $e) {
            throw new \Exception($e->reason());
        } catch (\Exception $e) {
            throw $e;
        }

        return $result;
    }
}

<?php

/**
 * CreateClaimMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\PhilHealthEClaimsEncryptor;
use App\Model\Entities\Claim;
use App\Model\Factory\ClaimNumberFactory;
use App\Model\Repositories\Contracts\ClaimRepository;
use App\Model\Repositories\Contracts\PersonRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Carbon;

/**
 *
 * Description of CreateClaimMutation
 *
 */
class CreateClaimMutation extends Mutation
{
    /** @var PersonRepository */
    private $persons;

    /** @var ClaimRepository */
    private $claims;

    /** @var EclaimsServiceInterface */
    private $eclaimsService;

    /**
     * CreateClaimMutation constructor.
     *
     * @param array $attributes
     * @param EclaimsServiceInterface $eclaimsService
     */
    public function __construct(
        $attributes = [],
        EclaimsServiceInterface $eclaimsService,
        PersonRepository $persons,
        ClaimRepository $claims
    )
    {
        $this->eclaimsService = $eclaimsService;
        $this->persons = $persons;
        $this->claims = $claims;
        parent::__construct( $attributes );
    }

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'createClaim'
    ];

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type( 'Claim' );
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'patient' => [ 'name' => 'patient', 'type' => Type::nonNull( Type::ID() ) ],
            'data' => [ 'name' => 'data', 'type' => Type::nonNull( Type::string() ) ],
        ];
    }


    /**
     * @param $root
     * @param array $args
     *
     * @return mixed
     */
    public function resolve( $root, $args )
    {
        $this->persons->resetScope();
        $person = $this->persons->find( @$args[ 'patient' ] );

        $claim = new Claim();
        $claim->claim_no = $claim->createNextClaimNumber();
        $claim->patient_id = $person[ 'id' ];
        $claim->data_json = $args[ 'data' ];
        $claim->save();

        return $this->claims->present( $claim );
    }
}

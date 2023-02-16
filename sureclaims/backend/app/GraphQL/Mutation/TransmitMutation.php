<?php

/**
 * TransmitMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\PhilHealthEClaimsEncryptor;
use App\Model\Entities\Transmittal;
use App\Model\Repositories\Contracts\TransmittalRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Carbon;

/**
 *
 * Description of TransmitMutation
 *
 */
class TransmitMutation extends Mutation
{
    /** @var EclaimsServiceInterface */
    private $eclaimsService;

    /** @var TransmittalRepository */
    private $transmittals;

    /**
     * TransmitMutation constructor.
     *
     * @param array $attributes
     * @param EclaimsServiceInterface $eclaimsService
     * @param TransmittalRepository $transmittals
     */
    public function __construct(
        $attributes = [],
        EclaimsServiceInterface $eclaimsService,
        TransmittalRepository $transmittals
    ) {
        $this->eclaimsService = $eclaimsService;
        $this->transmittals = $transmittals;
        parent::__construct($attributes);
    }

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'transmit'
    ];

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type('Transmittal');
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::ID())],
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
        $this->transmittals->resetScope()->skipPresenter();
        /** @var Transmittal $transmittal */
        $transmittal = $this->transmittals->find($args['id']);

        return $result;
    }
}

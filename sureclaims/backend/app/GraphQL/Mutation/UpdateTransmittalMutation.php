<?php

/**
 * UpdateTransmittalMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\Model\Entities\Claim;
use App\Model\Entities\Transmittal;
use App\Model\Repositories\Contracts\ClaimRepository;
use App\Model\Repositories\Contracts\TransmittalRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of UpdateTransmittalMutation
 *
 */
class UpdateTransmittalMutation extends Mutation
{
    /** @var ClaimRepository  */
    private $claims;

    /** @var TransmittalRepository  */
    private $transmittals;

    /**
     * UpdateTransmittalMutation constructor.
     *
     * @param array $attributes
     * @param TransmittalRepository $transmittal
     * @param ClaimRepository $claims
     *
     */
    public function __construct(
        $attributes = [],
        TransmittalRepository $transmittal,
        ClaimRepository $claims
    ) {
        $this->transmittals = $transmittal;
        $this->claims = $claims;
        parent::__construct($attributes);
    }

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'updateTransmittal'
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
            // 'transmittalNo' => ['name' => 'transmittalNo', 'type' => Type::nonNull(Type::string())],
            'notes' => ['name' => 'notes', 'type' => Type::string()],
            'claims' => ['name' => 'claims', 'type' => Type::listOf(Type::nonNull(Type::ID()))],
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
        $this->transmittals->resetScope();
        $this->claims->resetScope();

        \DB::beginTransaction();

        $this->transmittals->skipPresenter(true);

        /** @var Transmittal $transmittal */
        $transmittal = $this->transmittals->find($args['id']);

        $transmittal->claims->each(function (Claim $claim) {
            $claim->transmittal()->dissociate()->save();
        });

        // $transmittal->transmittal_no = $args['transmittalNo'];
        $transmittal->notes = @$args['notes'];
        $transmittal->save();

        $this->claims->skipPresenter(true);
        foreach ($args['claims'] as $claimID) {
            /** @var Claim $claim */
            $claim = $this->claims->find($claimID);
            $claim->transmittal()->associate($transmittal)->save();
        }

        \DB::commit();

        $this->transmittals->skipPresenter(false);
        $this->claims->skipPresenter(false);
        return $this->transmittals->present($transmittal);
    }
}

<?php

/**
 * UpdateClaimMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\GraphQL\Concerns\NormalizesFillableData;
use App\GraphQL\Concerns\ResolvesQueryFields;
use App\Model\Entities\Claim;
use App\Model\Repositories\Contracts\ClaimRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of UpdateClaimMutation
 *
 */

class UpdateClaimMutation extends Mutation
{
    use NormalizesFillableData, ResolvesQueryFields;

    /** @var ClaimRepository */
    protected $claims;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'updateClaim'
    ];

    /**
     * UpdateClaimMutation constructor.
     *
     * @param array $attributes
     * @param ClaimRepository $claims
     */
    public function __construct(
        $attributes = [],
        ClaimRepository $claims
    ) {
        $this->claims = $claims;
        parent::__construct($attributes);
    }

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type('Claim');
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::id())],
            'patient' => ['name' => 'patient', 'type' => Type::id()],
            'data' => ['name' => 'data', 'type' => Type::nonNull(Type::string())],
        ];
    }


    /**
     * @param $root
     * @param array $args
     * @param $context
     * @param ResolveInfo $info
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $this->claims->resetScope();
        $claimArgs = $this->normalizeFillableData(new Claim(), $args);

        /** @var Claim $claim */
        $claim = $this->claims->skipPresenter()->find($args['id']);
        if (empty($claim)) {
            throw new \Exception('Claim does not exist in the records');
        }

        $claim->patient_id = $args['patient'];
        $claim->data_json = $args['data'];
        $claim->save();

        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info);

        $this->claims->resetScope();
        $this->claims->parsePresenterIncludes($includes);

        $result = $this->claims->skipPresenter(false)->find($args['id']);
        return $result;
    }
}

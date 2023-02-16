<?php

/**
 * DeleteTransmittalMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\GraphQL\Concerns\NormalizesFillableData;
use App\GraphQL\Concerns\ResolvesQueryFields;
use App\Model\Entities\Transmittal;
use App\Model\Repositories\Contracts\TransmittalRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of DeleteTransmittalMutation
 *
 */

class DeleteTransmittalMutation extends Mutation
{
    use NormalizesFillableData, ResolvesQueryFields;

    /** @var TransmittalRepository */
    protected $transmittalRepository;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'deleteTransmittal'
    ];

    /**
     * DeleteTransmittalMutation constructor.
     *
     * @param array $attributes
     * @param TransmittalRepository $transmittalRepository
     */
    public function __construct(
        $attributes = [],
        TransmittalRepository $transmittalRepository
    ) {
        $this->transmittalRepository = $transmittalRepository;
        parent::__construct($attributes);
    }

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
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::id())],
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
        $this->transmittalRepository->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info);

        $this->transmittalRepository->resetScope();
        $this->transmittalRepository->parsePresenterIncludes($includes);

        /** @var Transmittal $transmittal */
        $transmittal = $this->transmittalRepository->skipPresenter()->find($args['id']);
        if (empty($transmittal)) {
            throw new \Exception('Transmittal does not exist in the records');
        }

        $result = $this->transmittalRepository->skipPresenter(false)->present($transmittal);
        $transmittal->delete();
        return $result;
    }
}

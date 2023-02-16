<?php

/**
 * UpdatePersonMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\GraphQL\Concerns\NormalizesFillableData;
use App\GraphQL\Concerns\ResolvesQueryFields;
use App\Model\Entities\Member;
use App\Model\Entities\Person;
use App\Model\Repositories\Contracts\MemberRepository;
use App\Model\Repositories\Contracts\PersonRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of UpdatePersonMutation
 *
 */

class UpdatePersonMutation extends Mutation
{
    use NormalizesFillableData, ResolvesQueryFields;

    /** @var PersonRepository */
    protected $personRepository;

    /** @var MemberRepository */
    protected $memberRepository;


    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'updatePerson'
    ];

    /**
     * UpdatePersonMutation constructor.
     *
     * @param array $attributes
     * @param PersonRepository $personRepository
     * @param MemberRepository $memberRepository
     */
    public function __construct(
        $attributes = [],
        PersonRepository $personRepository,
        MemberRepository $memberRepository
    ) {
        $this->personRepository = $personRepository;
        $this->memberRepository = $memberRepository;
        parent::__construct($attributes);
    }

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type('Person');
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::id())],
            'person' => ['name' => 'person', 'type' => Type::nonNull(GraphQL::type('PersonInput'))],
            'member' => ['name' => 'member', 'type' => GraphQL::type('MemberInput')],
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
        $this->personRepository->resetScope();
        $this->memberRepository->resetScope();

        $personArgs = $this->normalizeFillableData(new Person(), $args['person']);

        /** @var Person $person */
        $person = $this->personRepository->skipPresenter()->find($args['id']);
        if (empty($person)) {
            throw new \Exception('Person does not exist in the records');
        }

        $person->update($personArgs);

        $member = null;

        if (!empty($args['member'])) {
            $memberArgs = $this->normalizeFillableData(
                new Member(),
                $args['member']
            );

            if (empty($person->member)) {
                $person->member()->create($memberArgs);
            } else {
                $person->member->update($memberArgs);
            }
        }

        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info);

        $this->personRepository->resetScope();
        $this->personRepository->parsePresenterIncludes($includes);
        return $this->personRepository->skipPresenter(false)->find($args['id']);
    }
}

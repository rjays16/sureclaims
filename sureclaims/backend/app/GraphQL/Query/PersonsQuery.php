<?php

/**
 * PersonsQuery.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\Model\Repositories\Contracts\PersonRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of PersonsQuery
 *
 */

class PersonsQuery extends Query
{
    use ResolvesQueryFields;

    /** @var PersonRepository */
    protected $repository;

    /**
     * PersonsQuery constructor.
     *
     * @param array $attributes
     * @param PersonRepository $memberRepository
     */
    public function __construct($attributes = [], PersonRepository $memberRepository)
    {
        $this->repository = $memberRepository;
        parent::__construct($attributes);
    }

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'persons'
    ];

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return Type::listOf(GraphQL::type('Person'));
    }

    /**
     *
     */
    public function args()
    {
        return [
            'first' => ['name' => 'first', 'type' => Type::int()],
            'after' => ['name' => 'after', 'type' => Type::string()],
        ];
    }


    /**
     * @param $root
     * @param array $args
     * @param $context
     * @param ResolveInfo $info
     *
     * @return mixed
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $this->repository->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info, 'person');
        $this->repository->parsePresenterParameters($fieldSets, $includes);
        return ($this->repository->all())['persons'];
    }
}

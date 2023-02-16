<?php

/**
 * PersonsPageQuery.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\Model\Repositories\BaseRepository;
use App\Model\Repositories\Contracts\MemberRepository;
use App\Model\Repositories\Contracts\PersonRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of PersonsPageQuery
 *
 */

class PersonQuery extends Query
{
    use ResolvesQueryFields, PaginatesResults;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'person'
    ];


    /** @var BaseRepository */
    protected $members;

    /** @var BaseRepository */
    protected $persons;

    /**
     * PersonsPageQuery constructor.
     *
     * @param array $attributes
     * @param PersonRepository $persons
     * @param MemberRepository $memberRs
     */
    public function __construct(
        $attributes = [],
        PersonRepository $persons,
        MemberRepository $memberRs
    ) {
        $this->members = $memberRs;
        $this->persons = $persons;
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
     *
     */
    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::id()],
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
        $this->persons->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info);

        $this->persons
            ->parsePresenterIncludes($includes);

        return $this->persons->find($args['id']);
    }
}

<?php

/**
 * MembersPageQuery.php
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
 * Description of MembersPageQuery
 *
 */

class MembersPageQuery extends Query
{
    use ResolvesQueryFields, PaginatesResults;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'membersPage'
    ];


    /** @var BaseRepository */
    protected $members;

    /** @var BaseRepository */
    protected $persons;

    /**
     * MembersPageQuery constructor.
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
        return GraphQL::type('MembersPage');
    }

    /**
     *
     */
    public function args()
    {
        return [
            'id' => ['name' => 'name', 'type' => Type::id()],
            'name' => ['name' => 'name', 'type' => Type::string()],
            'page' => ['name' => 'page', 'type' => Type::int()],
            'pageSize' => ['name' => 'pageSize', 'type' => Type::int()],
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
            $this->resolveQueryFields($info, 'members');

        $this->persons
            ->parsePresenterParameters($includes)
            ->whereHas('member', null)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->orderBy('middle_name');

        if (!empty($args['name'])) {
            $this->persons->scopeQuery(function ($model) use ($args) {
                $model
                    ->where('last_name', 'LIKE', "{$args['name']}%")
                    ->orWhere('first_name', 'LIKE', "{$args['name']}%")
                    ->orWhere('middle_name', 'LIKE', "{$args['name']}%");
                return $model;
            });
        }

        $results = $this->paginate($this->persons, @$args['page'], @$args['pageSize']);
        $results['members'] = $results['persons'];
        unset($results['persons']);

        return $results;
    }
}

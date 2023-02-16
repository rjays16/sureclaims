<?php

/**
 * ClaimQuery.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\Model\Repositories\Contracts\ClaimRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of ClaimQuery
 *
 */

class ClaimQuery extends Query
{
    use ResolvesQueryFields, PaginatesResults;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'claim'
    ];

    /** @var ClaimRepository */
    protected $claims;

    /**
     * ClaimsPageQuery constructor.
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
        $this->claims->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info);

        $this->claims
            ->parsePresenterIncludes($includes);

        return $this->claims->find($args['id']);
    }
}

<?php

/**
 * IcdCodesQuery.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\Model\Repositories\Contracts\IcdCodeRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IcdCodesQuery
 *
 * @package App\GraphQL\Query
 */
class IcdCodesQuery extends Query
{
    use ResolvesQueryFields,
        PaginatesResults;

    /** @var IcdCodeRepository */
    private $icdCodes;

    /**
     * IcdCodesQuery constructor.
     *
     * @param array $attributes
     * @param IcdCodeRepository $icdCodes
     */
    public function __construct($attributes = [], IcdCodeRepository $icdCodes)
    {
        parent::__construct($attributes);

        $this->icdCodes = $icdCodes;
    }

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'icdCodes',
        'description' => 'Retrieves the list of registered ICD Codes'
    ];

    /**
     * @return GraphQL\Type\Definition\ListOfType|null
     */
    public function type()
    {
        return Type::listOf(GraphQL::type('IcdCode'));
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'search' => [ 'name' => 'search', 'type' => Type::string() ],
            'page' => ['name' => 'page', 'type' => Type::int()],
            'pageSize' => ['name' => 'pageSize', 'type' => Type::int()],
        ];
    }

    /**
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     *
     * @return array
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $this->icdCodes->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] = $this->resolveQueryFields($info);
        $this->icdCodes->parsePresenterIncludes($includes);

        $pageSize = array_get($args, 'pageSize', 5);

        if (isset($args['search'])) {
            $searchKey = $args['search'];
            $this->icdCodes->scopeQuery(function ($query) use ($searchKey, $pageSize) {
                /** @var Builder  $query */
                $query = $query->where('code', 'LIKE', "%{$searchKey}%");
                $query = $query->orWhere('description', 'LIKE', "%{$searchKey}%");
                return $query;
            });
        }

        return array_get($this->paginate($this->icdCodes), 'icdCodes');
    }
}

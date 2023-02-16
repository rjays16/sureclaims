<?php

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\Model\Repositories\Contracts\RvsCodeRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;
use Illuminate\Database\Eloquent\Builder;

class RvsCodesQuery extends Query
{
    use ResolvesQueryFields , PaginatesResults;

    private $rvsCodes;

    public function __construct($attributes = [], RvsCodeRepository $rvsCodes)
    {
        parent::__construct($attributes);

        $this->rvsCodes = $rvsCodes;
    }


    protected $attributes = [
        'name' => 'rvsCodes',
        'description' => 'Retrieves list of Registered RVS Codes'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('RvsCode'));
    }

    public function args()
    {
        return [
            'search' => [
                'name' => 'search',
                'type' => Type::string()
            ],
            'page' => [
                'name' => 'page',
                'type' => Type::string()
            ],
            'pageSize' => [
                'name' => 'pageSize',
                'type' => Type::int()
            ]
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
        $this->rvsCodes->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] = $this->resolveQueryFields($info);
        $this->rvsCodes->parsePresenterIncludes($includes);

        $pageSize = array_get($args, 'pageSize', 5);

        if (isset($args['search'])) {
            $searchKey = $args['search'];
            $this->rvsCodes->scopeQuery(function ($query) use ($searchKey, $pageSize) {
                /** @var Builder  $query */
                $query = $query->where('code', 'LIKE', "%{$searchKey}%");
                $query = $query->orWhere('description', 'LIKE', "%{$searchKey}%");
                return $query;
            });
        }

        return array_get($this->paginate($this->rvsCodes), 'rvsCodes');
    }


}

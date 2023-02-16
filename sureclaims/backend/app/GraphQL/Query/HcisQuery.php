<?php

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\Model\Repositories\Contracts\HciRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;
use Illuminate\Database\Eloquent\Builder;

class HcisQuery extends Query
{
    use ResolvesQueryFields,
        PaginatesResults;
    /** @var HciRepository */
    private $hcis;

    /**
     * HciQuery constructor.
     *
     * @param array $attributes
     * @param HciRepository $hci
     */

    protected $attributes = [
        'name' => 'hcis',
        'description' => 'Retrieves the list of registered HCI institutions'
    ];

    /**
     * IcdCodesQuery constructorh.
     *
     * @param array $attributes
     * @param HciRepository $icdCodes
     */
    public function __construct($attributes = [], HciRepository $hcis)
    {
        parent::__construct($attributes);

        $this->hcis = $hcis;
    }



    public function type()
    {
        return Type::listOf(GraphQL::type('Hci'));

    }

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

        $this->hcis->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] = $this->resolveQueryFields($info);
        $this->hcis->parsePresenterIncludes($includes);

        $pageSize = array_get($args, 'pageSize', 5);

        if (isset($args['search'])) {
            $searchKey = $args['search'];
            $this->hcis->scopeQuery(function ($query) use ($searchKey, $pageSize) {
                /** @var Builder  $query */
                $query = $query->where('hospital_name', 'LIKE', "%{$searchKey}%");
                return $query;
            });
        }

        return array_get($this->paginate($this->hcis), 'hcis');

    }
}

<?php

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\GraphQL\Type\SecondCaseRateType;
use App\Model\Repositories\Contracts\SecondCaseRateRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

class SecondCaseRatesQuery extends Query
{

    use ResolvesQueryFields, PaginatesResults;

    protected $attributes = [
        'name' => 'secondCaseRates',
        'description' => 'A query'
    ];
    /**
     * @var SecondCaseRateRepository
     */
    private $secondCaseRates;

    /**
     * SecondCaseRatesQuery constructor.
     * @param array $attributes
     * @param SecondCaseRateRepository $secondCaseRates
     */
    public function __construct( $attributes = [], SecondCaseRateRepository $secondCaseRates )
    {
        parent::__construct( $attributes );
        $this->secondCaseRates = $secondCaseRates;
    }


    public function type()
    {
        return Type::listOf( GraphQL::type( ( new SecondCaseRateType )->get( 'name' ) ) );
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

    public function resolve( $root, $args, $context, ResolveInfo $info )
    {
        $this->secondCaseRates->resetScope();
        [ 'includes' => $includes, 'fieldSets' => $fieldSets ] = $this->resolveQueryFields( $info );
        $this->secondCaseRates->parsePresenterIncludes( $includes );

        $pageSize = array_get( $args, 'pageSize', 5 );

        if ( isset( $args[ 'search' ] ) ) {
            $searchKey = $args[ 'search' ];
            $this->secondCaseRates->scopeQuery( function ( $query ) use ( $searchKey, $pageSize ) {
                /** @var Builder $query */
                $query = $query->where( 'code', 'LIKE', "%{$searchKey}%" );
                $query = $query->orWhere( 'description', 'LIKE', "%{$searchKey}%" );
                $query = $query->orWhere( 'item_code', 'LIKE', "%{$searchKey}%" );
                $query = $query->orWhere( 'item_description', 'LIKE', "%{$searchKey}%" );
                return $query;
            } );
        }

        return array_get( $this->paginate( $this->secondCaseRates ), 'secondCaseRates' );
    }
}

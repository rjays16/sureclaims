<?php

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\GraphQL\Type\CaseRateType;
use App\Model\Repositories\Contracts\CaseRateRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;
use Illuminate\Database\Query\Builder;

class CaseRatesQuery extends Query
{

    use ResolvesQueryFields, PaginatesResults;

    protected $attributes = [
        'name' => 'caseRates',
        'description' => 'Retrieves list of Registered Case Rate Codes'
    ];
    /**
     * @var CaseRateRepository
     */
    private $caseRates;

    /**
     * CaseRatesQuery constructor.
     * @param array $attributes
     * @param CaseRateRepository $caseRates
     */
    public function __construct( $attributes = [], CaseRateRepository $caseRates )
    {
        parent::__construct( $attributes );
        $this->caseRates = $caseRates;
    }


    public function type()
    {
        return Type::listOf( GraphQL::type( ( new CaseRateType )->get( 'name' ) ) );
    }

    public function args()
    {
        return [
            'page' => [
                'name' => 'page',
                'type' => Type::string()
            ],
            'pageSize' => [
                'name' => 'pageSize',
                'type' => Type::int()
            ],
            'search' => [
                'name' => 'search',
                'type' => Type::string()
            ],
            'secondOnly' => [
                'type' => Type::boolean(),
                'description' => 'Whether to find items that are allowed to second case rate.'
            ],
        ];
    }

    public function resolve( $root, $args, $context, ResolveInfo $info )
    {
        $this->caseRates->resetScope();
        [ 'includes' => $includes, 'fieldSets' => $fieldSets ] = $this->resolveQueryFields( $info );
        $this->caseRates->parsePresenterIncludes( $includes );

        $search = array_get( $args, 'search' );
        $pageSize = array_get( $args, 'pageSize', 5 );
        $secondOnly = array_get($args, 'secondOnly', false);

        $this->caseRates->scopeQuery( function ( $query ) use ( $search, $secondOnly ) {
            /** @var Builder $query */
            if ( $search ) {
                $query = $query->where(function($query) use ($search) {
                    $query->where( 'code', 'LIKE', "%{$search}%" )
                    ->orWhere( 'description', 'LIKE', "%{$search}%" )
                    ->orWhere( 'item_code', 'LIKE', "%{$search}%" )
                    ->orWhere( 'item_description', 'LIKE', "%{$search}%" );
                });
            }

            if ( $secondOnly ) {
                $query = $query->where(function($query) use ($secondOnly) {
                    $query->where( 'allow_second', '=', 1);
                });
            }

            /**
             * Return by hospital_type
             */
            if (config('eclaims.primary_hospital', false)) {
                $query = $query->primary();
            } else {
                $query = $query->tertiary();
            }

            /**
             * Scopes CaseRateScopes
             * Get only the active caserates
             */
            $query = $query->active();

            return $query;
        } );

        return array_get( $this->paginate( $this->caseRates ), 'caseRates' );
    }
}

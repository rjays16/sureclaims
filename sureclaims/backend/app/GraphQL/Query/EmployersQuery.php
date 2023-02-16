<?php

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\PhilHealthEClaimsEncryptor;
use App\Model\Entities\IndexQueryTable;
use App\Model\Repositories\Contracts\EmployerRepository;
use App\Model\Repositories\Contracts\IndexQueryTableRepository;
use App\Model\Repositories\IndexQueryTableRepositoryEloquent;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;
use Mockery\Exception;
use Vyuldashev\XmlToArray\XmlToArray;

class EmployersQuery extends Query
{

    use ResolvesQueryFields,
        PaginatesResults;

    protected $attributes = [
        'name' => 'employers'
    ];

    /** @var EmployerRepository */
    protected $employers;
    /**
     * @var IndexQueryTableRepositoryEloquent
     */
    private $indexes;
    /**
     * @var EclaimsServiceInterface
     */
    private $eclaimsService;

    /**
     * EmployersQuery constructor.
     * @param array $attributes
     * @param EclaimsServiceInterface $eclaimsService
     * @param EmployerRepository $employers
     * @param IndexQueryTableRepository $indexes
     */
    public function __construct(
        $attributes = [],
        EclaimsServiceInterface $eclaimsService,
        EmployerRepository $employers,
        IndexQueryTableRepository $indexes
    )
    {
        parent::__construct( $attributes );
        $this->employers = $employers;
        $this->indexes = $indexes;
        $this->eclaimsService = $eclaimsService;
    }

    public function type()
    {
        return Type::listOf( GraphQL::type( 'Employer' ) );
    }

    public function args()
    {
        return [
            'name' => [
                'name' => 'name',
                'type' => Type::string(),
                'description' => 'If empty, employers list will always be empty.'
            ]
        ];
    }

    /**
     * If index is not valid or empty: fetch from PHIC then update Employer table.
     * Then always query Employers table
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     * @return mixed
     *
     * @throws \Exception
     */
    public function resolve( $root, $args, $context, ResolveInfo $info )
    {
        $name = trim( array_get( $args, 'name' ) ?: "" );

        if ( !$name ) {
            return [];
        }

        $index = $this->indexes->findIndexQuery( $name, $this->employers );

        if ( !$index ) {
            $result = $this->eclaimsService->searchEmployer( null, $name );
            $employers = array_get($result, 'eEMPLOYERS.employer', []);
            if (empty($employers[0])) {
                $employers = [$employers];
            }

            collect( $employers )->each( function ( $item ) {
                // pop first item which is "@attributes"
                $item = $item['@attributes'];
                $this->employers->updateOrCreate( [
                    'pen' => $item[ 'pPEN' ]
                ], [
                    'pen' => $item[ 'pPEN' ],
                    'name' => $item[ 'pEmployerName' ],
                    'address' => $item[ 'pEmployerAddress' ]
                ] );
            } );

            // create index
            $this->indexes->createIndex( $name, $this->employers );
        }

        if ( !empty( $args[ 'name' ] ) ) {
            $this->employers->scopeQuery( function ( $query ) use ( $name ) {
                $query = $query
                    ->where( 'name', 'LIKE', "%{$name}%" )
                    ->orWhere( 'pen', 'LIKE', "%{$name}%" );
                return $query;
            } );
        }

        $data = $this->paginate( $this->employers );

        return array_get( $data, 'employers', [] );
    }
}

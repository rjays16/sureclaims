<?php

/**
 * DoctorsQuery.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\Model\Criteria\Person\WithNameSearchCriteria;
use App\Model\Repositories\Contracts\DoctorRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of DoctorsQuery
 *
 */
class DoctorsQuery extends Query
{
    use ResolvesQueryFields,
        PaginatesResults;

    /** @var DoctorRepository */
    protected $doctors;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'doctors'
    ];

    /**
     * DoctorsQuery constructor.
     *
     * @param array $attributes
     * @param DoctorRepository $doctors
     */
    public function __construct( $attributes = [], DoctorRepository $doctors )
    {
        $this->doctors = $doctors;
        parent::__construct( $attributes );
    }

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type( 'DoctorsPage' );
    }

    /**
     *
     */
    public function args()
    {
        return [
            'name' => [ 'name' => 'name', 'type' => Type::string() ],
            'page' => [ 'name' => 'page', 'type' => Type::int() ],
            'pageSize' => [ 'name' => 'pageSize', 'type' => Type::int() ],
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
    public function resolve( $root, $args, $context, ResolveInfo $info )
    {
        $this->doctors->resetScope();
        [ 'includes' => $includes, 'fieldSets' => $fieldSets ] =
            $this->resolveQueryFields( $info, 'doctors' );

        $this->doctors
            ->parsePresenterIncludes( $includes )
            ->orderBy( 'last_name' )
            ->orderBy( 'first_name' )
            ->orderBy( 'middle_name' );

        if ( !empty( $args[ 'name' ] ) ) {
            $this->doctors->pushCriteria( new WithNameSearchCriteria( $args[ 'name' ] ) );
            $this->doctors->scopeQuery(function ($query) use ($args) {
                /** @var Model|Builder $query  */
                $query = $query->where('id', '=', $args['name'], 'OR');
                return $query;
            });
        }

        $result = $this->paginate( $this->doctors, @$args[ 'page' ], @$args[ 'pageSize' ] );
        return $result;
    }
}

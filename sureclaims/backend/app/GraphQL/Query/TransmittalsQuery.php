<?php

/**
 * TransmittalsQuery.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\Model\Repositories\Contracts\TransmittalRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of TransmittalsQuery
 *
 */
class TransmittalsQuery extends Query
{
    use ResolvesQueryFields, PaginatesResults;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'transmittals'
    ];

    /** @var TransmittalRepository */
    protected $transmittals;

    /**
     * TransmittalsQuery constructor.
     *
     * @param array $attributes
     * @param TransmittalRepository $transmittals
     */
    public function __construct(
        $attributes = [],
        TransmittalRepository $transmittals
    ) {
        $this->transmittals = $transmittals;
        parent::__construct($attributes);
    }

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type('TransmittalsPage');
    }

    /**
     *
     */
    public function args()
    {
        return [
            'dateRange' => ['name' => 'dateRange', 'type' => Type::listOf(Type::string())],
            'page' => ['name' => 'page', 'type' => Type::int()],
            'transmittalNumber' => ['name' => 'transmittalNumber', 'type' => Type::string()],
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
	ini_set('memory_limit', -1);
        $this->transmittals->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info, 'transmittals');

        $transmittalNumber = array_get( $args, 'transmittalNumber',  null);
        $dateRange = array_get( $args, 'dateRange',  null);


        $this->transmittals->parsePresenterIncludes($includes);
        if(!is_null($transmittalNumber)){
            $this->transmittals
                ->scopeQuery(function($query) use ($transmittalNumber) {
                    $query = $query->where( 'transmittal_no', 'LIKE', "%{$transmittalNumber}%" );
                    return $query;
                });
        }else{
            $this->transmittals
                ->scopeQuery( function ( $query ) use ($dateRange) {
                    /** @var Builder $query */
                    if ($dateRange) { $query = $query->whereBetween('created_at', $dateRange); }
                    return $query;
                })->orderBy('created_at', 'desc');
        }

        $result = $this->paginate($this->transmittals, @$args['page'], @$args['pageSize']);
        return $result;
    }
}

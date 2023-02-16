<?php

/**
 * ClaimsQuery.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\Model\Criteria\Person\WithNameSearchCriteria;
use App\Model\Entities\Claim;
use App\Model\Repositories\Contracts\ClaimRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of ClaimsQuery
 *
 */

class ClaimsQuery extends Query
{
    use ResolvesQueryFields, PaginatesResults;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'claims'
    ];

    /** @var ClaimRepository */
    protected $claims;

    /**
     * ClaimsQuery constructor.
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

    private function getIsAttached($isAttached, $transmittal)
    {
        if (!is_null($isAttached)) {
            $this->claims->scopeQuery(function($query) use ($isAttached, $transmittal) {
                return $query->where(function($query) use ($isAttached, $transmittal) {
                    $query = $query->attachedToTransmittal($isAttached);
                    if (!is_null($transmittal)) {
                       $query = $query->orWhere('transmittal_id', '=', $transmittal);
                    }
                    return $query;
                });
            });
        }
    }

    private function getClaimNo($claimNo)
    {
        if($claimNo){
            $this->claims
                    ->scopeQuery(function($query) use ($claimNo) {
                        $query = $query->where( 'claim_no', 'LIKE', "%{$claimNo}%" );
                        return $query;
            });
        }
    }
    private function getName($name)
    {
        if ($name) {
            /**@var $closure \Prettus\Repository\Contracts\closure*/
            $closure = function ($query) use ($name)
            {
                return (new WithNameSearchCriteria($name))
                ->apply($query, $this->claims);
            };
            $this->claims->whereHas('patient', $closure);
        }
    }

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type('ClaimsPage');
    }

    /**
     *
     */
    public function args()
    {
        return [
            'name' => ['name' => 'name', 'type' => Type::string()],
            'claimNo' => ['name' => 'claimNo', 'type' => Type::string()],
            'page' => ['name' => 'page', 'type' => Type::int()],
            'status' => ['name' => 'status', 'type' => Type::string()],
            'pageSize' => ['name' => 'pageSize', 'type' => Type::int()],
            'isAttached' => [
                'type' => Type::boolean(),
                'description' => 'Filter claims if attached to transmittal'
            ],
            'transmittal' => [
                'type' => Type::id(),
                'description' => 'Filter claims attached to transmittal id'
            ]
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
            $this->resolveQueryFields($info, 'claims');

        $this->claims
            ->parsePresenterIncludes($includes)
            ->orderBy('created_at', 'desc');

       
        $name = array_get($args, 'name', '');
        $claimNo = array_get($args, 'claimNo', '');
        $status = array_get($args, 'status', '');

        
         /**
         * Filter by name
         */
        $this->getName($name);

         /**
         * Filter by claim no.
         */
        $this->getClaimNo($claimNo);
        

        /**
         * Filter whether attached to transmittal
         */
        $isAttached = array_get($args, 'isAttached', null);
        $transmittal = array_get($args, 'transmittal', null);
        
        $this->getIsAttached($isAttached, $transmittal);

         /**
         * Filter by status
         */
        if ($status != '') {
            $this->claims
                    ->scopeQuery(function($query) use ($status) {
                        $ClaimsModel = new Claim;
                        $query = $ClaimsModel->getCheckStatus($status, $query);
                        return $query;
                    });
        }

        $result = $this->paginate($this->claims, @$args['page'], @$args['pageSize']);
        return $result;
    }
}

<?php

/**
 * LatestClaimNumber.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Query;

use App\Model\Entities\Claim;
use App\Model\Factory\ClaimNumberFactory;
use App\Model\Repositories\ClaimRepositoryEloquent;
use App\Model\Repositories\Contracts\ClaimRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class LatestClaimNumber
 *
 * @package App\GraphQL\Query
 */
class LatestClaimNumberQuery extends Query
{
    protected $attributes = [
        'name' => 'latestClaimNumber',
        'description' => 'A query'
    ];
    /**
     * @var ClaimRepositoryEloquent
     */
    private $claims;

    /**
     * @return Type
     */
    public function type()
    {
        return Type::string();
    }

    /**
     * @return array
     */
    public function args()
    {
        return [];
    }

    public function __construct( $attributes = [], ClaimRepositoryEloquent $claims )
    {
        parent::__construct( $attributes );
        $this->claims = $claims;
    }


    /**
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     *
     * @return array
     */
    public function resolve( $root, $args, $context, ResolveInfo $info )
    {
        $options = config( 'eclaims.auto_claim_number', false );

        if ( !$options ) {
            return null;
        }

        /** @var Claim $claim */
        $claim = ( new Claim() )->getRecentClaim();

        $number = ( new ClaimNumberFactory( $claim, $options ) )->make();

        return $number;
    }
}

<?php

/**
 * RefreshClaimStatusMutation.php
 *
 * @author Jolly Caralos <jadcaralos@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App;
use App\GraphQL\PaginatesResults;
use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\Exceptions\PhilhealthException;
use App\Model\Entities\Claim;
use App\Model\Entities\ClaimStatusDetail;
use App\Model\Repositories\Contracts\ClaimRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Collection;

/**
 * Class RefreshClaimStatusMutation
 * @package App\GraphQL\Mutation
 */
class RefreshClaimStatusMutation extends Mutation
{

    use PaginatesResults;

    protected $attributes = [
        'name' => 'refreshClaimStatus',
        'description' => 'Recheck claim status from PHIC GetClaimStatus service'
    ];

    /**
     * @var EclaimsServiceInterface
     */
    private $eclaimsService;
    /**
     * @var ClaimRepository
     */
    private $claims;

    /**
     * EmployersQuery constructor.
     * @param array $attributes
     * @param EclaimsServiceInterface $eclaimsService
     * @param ClaimRepository $claims
     */
    public function __construct(
        $attributes = [],
        EclaimsServiceInterface $eclaimsService,
        ClaimRepository $claims
    )
    {
        parent::__construct( $attributes );
        $this->eclaimsService = $eclaimsService;
        $this->claims = $claims;
    }

    public function type()
    {
        return Type::listOf( GraphQL::type( 'Claim' ) );
    }

    public function args()
    {
        return [
            'ids' => [
                'type' => Type::nonNull( Type::listOf( Type::id() ) ),
                'description' => 'Claim id\'s'
            ]
        ];
    }

    /**
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     * @return array
     */
    public function resolve( $root, $args, $context, ResolveInfo $info )
    {
        $claimIds = $args[ 'ids' ];

        if ( empty( $claimIds ) ) {
            App::abort( 400, 'Parameter Claim id\'s cannot be empty!' );
        }

        try {
            /**
             * Find claims and get all lhio_series_no
             */
            /** @var Collection $series */
            $series = $this->claims->skipPresenter()->findWhereIn( 'id', $claimIds );
            $lhioSeriesNos = array_pluck( $series, 'lhio_series_no' );


            /**
             * Get data from PHIC
             */
            $result = $this->eclaimsService->getClaimStatus( $lhioSeriesNos );
            
            \DB::beginTransaction();

            /**
             * Extract/Update CLAIMS
             */
            $claims = array_get( $result, 'STATUS.CLAIM', [] );

            foreach ( $claims as $phicClaim ) {
                $claim = $series->first( function ( Claim $value ) use ( $phicClaim ) {
                    return $value->lhio_series_no === array_get($phicClaim, '@attributes.pClaimSeriesLhio');
                } );
                $claim->status = array_get($phicClaim, '@attributes.pStatus');
                $statusResult = array_get( $result, 'STATUS');

                $status = new ClaimStatusDetail();
                $status->patient_id = $claim['patient_id'];
                $status->claim_id = $claim['id'];
                $status->date_inquiry = formatDbDatetime(
                    array_get($statusResult, '@attributes.pAsOf') . ' ' .
                    array_get($statusResult, '@attributes.pAsOfTime'));
                $status->as_of_date = formatDbDate(array_get($statusResult, '@attributes.pAsOf'));
                $status->as_of_time = formatDbTime(array_get($statusResult, '@attributes.pAsOfTime'));
                $status->claim_date_refile = formatDbDate(array_get($phicClaim, '@attributes.pClaimDateRefile'));
                $status->claim_date_received = formatDbDate(array_get($phicClaim, '@attributes.pClaimDateReceived'));
                $status->data_json = json_encode($result);

                $status->save();
                $claim->save();
            }

            \DB::commit();

        } catch ( PhilhealthException $e ) {
            throw new \Exception($e->reason());
        } catch ( \Exception $e ) {
            throw $e;
        }

        $result = $this->claims->skipPresenter( false )->findWhereIn( 'id', $claimIds );
        return array_get( $result, 'claims' );
    }
}

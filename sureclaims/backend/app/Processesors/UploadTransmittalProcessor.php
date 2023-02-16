<?php
/**
 * UploadTransmittalProcessor.php
 *
 * @author Jolly Caralos <jadcaralos@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 */


namespace App\Processesors;


use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\Exceptions\PhilhealthException;
use App\Model\Entities\Transmittal;
use App\Model\Repositories\ClaimRepositoryEloquent;
use App\Model\Transformers\TransmittalXmlTransformer;
use Log;

class UploadTransmittalProcessor
{
    /**
     * @var EclaimsServiceInterface
     */
    private $service;
    /**
     * @var TransmittalXmlTransformer
     */
    private $transformer;
    /**
     * @var ClaimRepositoryEloquent
     */
    private $claims;

    public function __construct(
        EclaimsServiceInterface $service,
        TransmittalXmlTransformer $transformer,
        ClaimRepositoryEloquent $claims
    )
    {
        $this->service = $service;
        $this->transformer = $transformer;
        $this->claims = $claims;
    }


    public function upload( Transmittal $transmittal )
    {
        $this->claims->skipPresenter();

        if ( $transmittal->status === Transmittal::STATUS_UPLOADED ) {
            $this->mapTransmittal( $transmittal );
        } else {
            $this->uploadTransmittal( $transmittal );
        }
    }

    public function mapTransmittal( Transmittal $transmittal ): void
    {
        $this->claims->skipPresenter();
        Log::info( "Mapping Transmittal # {$transmittal->transmittal_no} ..." );

        try {
            $result = $this->service->getUploadedClaimsMap( $transmittal->receipt_ticket_no );
            $mapping = array_get( $result, 'eCONFIRMATION.MAPPING', [] );

            $mapData = array_get( $mapping, '@attributes', [] );

            if(!$mapData) {
                foreach ( $mapping as $map ) {

                    $mapData = array_get( $map, '@attributes', [] );

                    if ( @$mapData[ 'pClaimNumber' ] ) {
                        $this->updateClaimMap(
                            $mapData[ 'pClaimNumber' ],
                            @$mapData[ 'pClaimSeriesLhio' ]
                        );
                    }
                }
            } else {
                if ( @$mapData[ 'pClaimNumber' ] ) {
                    $this->updateClaimMap(
                        $mapData[ 'pClaimNumber' ],
                        @$mapData[ 'pClaimSeriesLhio' ]
                    );
                }
            }

            $transmittal->status = Transmittal::STATUS_MAPPED;
            $transmittal->save();

            Log::info( "Transmittal successfully mapped!" );
        } catch ( \Exception $e ) {
            Log::error( $e->getMessage() );
        }
    }

    private function updateClaimMap($claimNo, $seriesNo)
    {
        /** @var Claim $claim */
        $claim = $this->claims->findByField(
            'claim_no',
            $claimNo
        )->first();

        if (!$claim) {
            throw new \Exception('Claim not found when mapping LHIO series number.');
        }

        $claim->lhio_series_no = $seriesNo;
        $claim->save();
    }

    public function uploadTransmittal( Transmittal $transmittal ): void
    {
        try {
            $xml = $this->transformer->xmlize( $transmittal );
            Log::info( "Uploading Transmittal # {$transmittal->transmittal_no} ..." );
            $result = $this->service->eClaimsUpload( $xml );

            $receiptTicketNumber = array_get( $result, 'eRECEIPT.@attributes.pReceiptTicketNumber' );
            if ( $receiptTicketNumber ) {
                // Successful upload
                $transmittal->receipt_ticket_no = $receiptTicketNumber;
                $transmittal->transmit_date = ec_from_date(
                    array_get( $result, 'eRECEIPT.@attributes.pTransmissionDate' )
                );
                $transmittal->transmit_time = ec_from_time(
                    array_get( $result, 'eRECEIPT.@attributes.pTransmissionTime' )
                );
                $transmittal->transmittal_control_code = array_get( $result,
                    'eRECEIPT.@attributes.pTransmissionControlNumber' );
                $transmittal->status = Transmittal::STATUS_UPLOADED;
                $transmittal->save();

                Log::info( 'Transmittal successfully uploaded!' );
            } else {
                // Unsuccessful upload
                throw new \Exception( 'Unsuccessful upload' );
            }
        } catch (PhilhealthException $e) {

            Log::error( $e->reason() );
            $transmittal->transmit_errors = $e->reason();
            $transmittal->save();

        } catch ( \Exception $e ) {
            Log::error( $e->getMessage() );
            // $this->connection->get()->rollBack();

            /*
            $collectErrors = [];
            $transmitErrors = array_get($result, 'eRECEIPT.REMARKS');
            foreach ($transmitErrors as $transmitError) {
                $collectErrors[] = [
                    'code' => array_get($transmitError, '@attributes.pErrCode'),
                    'description' => array_get($transmitError, '@attributes.pErrDescription'),
                ];
            }
            $transmittal->transmit_errors = json_encode($collectErrors);
            */
            $transmittal->transmit_errors = $e->getMessage();
            $transmittal->save();
        }
        // $this->connection->get()->commit();
    }
}
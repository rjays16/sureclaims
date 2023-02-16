<?php

/**
 * ClaimTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Unit\Model\Entities;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\Exceptions\InvalidXMLException;
use App\Model\Entities\Claim;
use App\Model\Factory\ClaimNumberFactory;
use App\Model\Transformers\ClaimXmlTransformer;
use Tests\Concerns\ProvidesJsonData;
use Tests\Concerns\ProvidesXmlData;
use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class ClaimTest
 * @package Tests\Unit\Model\Entities
 */
class ClaimTest extends GraphQLTestCase
{
    use ProvidesXmlData, ProvidesJsonData;

    /** @var EclaimsServiceInterface */
    private $eclaims;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->eclaims = \App::make( EclaimsServiceInterface::class );
    }

    /**
     * @test
     */
    public function shouldProcessXmlDataBeforeSaving()
    {
        $claim = new Claim();
        $claim->data_json = $this->provideClaimJson();
        $claim->save();

        $this->assertFalse( $claim->is_valid );
        $this->assertNotEmpty( $claim->validation_errors );
        $this->assertNotEmpty( $claim->admission_date );
        $this->assertNotEmpty( $claim->discharge_date );

        // Check from database)
        $data = $this->claims()->find( $claim->id );
        $this->assertNotEmpty( $data );
    }

    /**
     * @test
     */
    public function shouldSuccessfullyValidateSampleXml()
    {
        $xml = $this->providePhicTransmittalXml();

        $errors = null;
        try {
            $this->eclaims->validateTransmittalXML( $xml );
        } catch ( InvalidXMLException $exception ) {
            $errors = $exception->validationErrors();
        }

        $this->assertEmpty( $errors );
    }

    /**
     * @test
     */
    public function shouldCorrectlyConvertJsonStructureToXml()
    {
        $layout = $this->provideTransmittalWrapperXml();

        $transformer = new ClaimXmlTransformer();
        $data = \GuzzleHttp\json_decode( $this->provideClaimJsonWithRepeatingIcdCodes(), true );

        // Insert transformed claim XML into structure
        $root = 'CLAIM';
        $xml = $transformer->xmlize( null, $data, $root );
        $errors = null;
        try {
            $this->eclaims->validateTransmittalXML( strtr( $layout, [
                '{xml}' => $xml
            ] ) );
        } catch ( InvalidXMLException $exception ) {
            $errors = $exception->validationErrors();
        }

        // This will produce errors
        $this->assertNotEmpty( $errors );

        $sxe = new \SimpleXMLElement( $xml );

        $this->assertNotEmpty( $sxe->CF2->DIAGNOSIS );
        $this->assertNotEmpty( $sxe->CF2->DIAGNOSIS->DISCHARGE );
        $this->assertEmpty( $sxe->CF2->DIAGNOSIS->DISCHARGE->RVSCODE );
        $this->assertNotEmpty( $sxe->CF2->DIAGNOSIS->DISCHARGE->ICDCODE );
        $this->assertSameSize( range( 1, 3 ), $sxe->CF2->DIAGNOSIS->DISCHARGE->ICDCODE );
    }

    /**
     * @test
     */
    public function shouldCreateNextClaimNumber()
    {
        // Setup config
        $eclaims = include(base_path('tests/resources/config') . '/eclaims.php');
        config([
            'eclaims.auto_claim_number' => array_get($eclaims, 'auto_claim_number')
        ]);

        $options = config( 'eclaims.auto_claim_number', false );

        $claim = new Claim();
        $number = $claim->createNextClaimNumber();

        $this->assertNotEmpty( $number );

        $this->assertEquals(
            ( new ClaimNumberFactory( $claim, $options ) )->make(),
            $number
        );

        $this->provideClaims( 4 )->each( function ( Claim $claim ) {
            $claim->claim_no = $claim->createNextClaimNumber();
            $claim->save();
        } );

        $this->assertEquals(
            ClaimNumberFactory::format(5, $options),
            (new Claim)->createNextClaimNumber()
        );
    }

}

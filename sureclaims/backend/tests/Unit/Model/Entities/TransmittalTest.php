<?php

namespace Tests\Unit\Model\Entities;

use App\Model\Entities\Transmittal;
use App\Model\Factory\TransmittalNumberFactory;
use Tests\Feature\GraphQL\GraphQLTestCase;

class TransmittalTest extends GraphQLTestCase
{
    /**
     * @test
     */
    public function shouldCreateNextTransmittalNumber()
    {
        // Setup config
        $eclaims = include( base_path( 'tests/resources/config' ) . '/eclaims.php' );
        config( [
            'eclaims.auto_transmittal_number' => array_get( $eclaims, 'auto_transmittal_number' )
        ] );

        $options = config( 'eclaims.auto_transmittal_number', false );

        $transmittal = new Transmittal();
        $number = $transmittal->createNextTransmittalNumber();

        $this->assertNotEmpty( $number );

        $this->assertEquals(
            ( new TransmittalNumberFactory( $transmittal, $options ) )->make(),
            $number
        );

        $this->provideTransmittals( 4 )->each( function ( Transmittal $transmittal ) {
            $transmittal->transmittal_no = $transmittal->createNextTransmittalNumber();
            $transmittal->save();
        } );

        $this->assertEquals(
            TransmittalNumberFactory::format( 5, $options ),
            ( new Transmittal() )->createNextTransmittalNumber()
        );
    }
}

<?php

/**
 * TransmittalXmlTransformerTest.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Unit\Model\Transformers;

use App\Model\Entities\Claim;
use App\Model\Transformers\TransmittalTransformer;
use App\Model\Transformers\TransmittalXmlTransformer;
use Tests\Concerns\InteractsWithTenancy;
use Tests\Concerns\ProvidesModels;
use Tests\Concerns\RefreshesDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class TransmittalXmlTransformerTest
 * @package Tests\Unit\Model\Transformers
 */
class TransmittalXmlTransformerTest extends TestCase
{
    use InteractsWithTenancy,
        RefreshesDatabase,
        ProvidesModels,
        WithFaker;

    /**
     * @test
     */
    public function shouldCorrectlyXmlizeTransmittal()
    {
        $transmittal = $this->provideTransmittal();
        $transmittal->save();

        $claimsCount = 3;
        $this->provideClaims($claimsCount)->each(function(Claim $claim) use ($transmittal) {
            $claim->transmittal()->associate($transmittal)->save();
        });

        $transformer = new TransmittalXmlTransformer();
        $xml = $transformer->xmlize($transmittal);


        $sxe = new \SimpleXMLElement($xml);
        $this->assertEquals((string) $sxe->eTRANSMITTAL['pHospitalTransmittalNo'], $transmittal->transmittal_no);
        $this->assertEquals((string) $sxe->eTRANSMITTAL['pTotalClaims'], (string )$transmittal->claims->count());
    }
}

<?php

namespace Tests\Unit\Model\Transformers;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\Exceptions\InvalidXMLException;
use App\Model\Transformers\ClaimXmlTransformer;
use Tests\Concerns\ProvidesJsonData;
use Tests\Concerns\ProvidesXmlData;
use Tests\TestCase;

/**
 * Class ClaimXmlTransformerTest
 * @package Tests\Unit\Model\Transformers
 */
class ClaimXmlTransformerTest extends TestCase
{
    use ProvidesJsonData, ProvidesXmlData;

    /**
     * @test
     */
    public function shouldSanitizeEmptyElements()
    {
        $transformer = new ClaimXmlTransformer();
        $data = \GuzzleHttp\json_decode($this->provideClaimJsonForSanitizationCheck(), true);

        $this->assertNotEmpty($data, 'Invalid JSON format encountered');

        $rawXml = $transformer->xmlize(null, $data, false);
        $document = new \DOMDocument();
        $document->loadXML($rawXml);
        $this->assertNotEmpty($document->getElementsByTagName('HEMODIALYSIS')->item(0));
        $this->assertNotEmpty($document->getElementsByTagName('CF3')->item(0));
        $this->assertNotEmpty($document->getElementsByTagName('CF3_NEW')->item(0));


        $rawXml = $transformer->xmlize(null, $data, true);
        $document = new \DOMDocument();
        $document->loadXML($rawXml);
        $this->assertEmpty($document->getElementsByTagName('HEMODIALYSIS')->item(0));
        $this->assertEmpty($document->getElementsByTagName('CF3')->item(0));
        $this->assertEmpty($document->getElementsByTagName('CF3_NEW')->item(0));
        $this->assertNotEmpty($document->getElementsByTagName('PERITONEAL')->item(0));

        $service = \App::make(EclaimsServiceInterface::class);
        $errors = [];
        try {
            $service->validateClaimXML($rawXml);
        } catch (InvalidXMLException $e) {
            $errors = $e->validationErrors();
        }

        $this->assertNotEmpty($errors);
    }

    /**
     * @test
     */
    public function shouldJsonizeCorrectly()
    {
        $transformer = new ClaimXmlTransformer();
        $rawXml = $this->providePhicClaimXml();
        $json = $transformer->jsonize($rawXml);
        $data = json_decode($json, true);
        $xml = $transformer->xmlize(null, $data);

        $sxe = new \SimpleXMLElement($xml);
        $this->assertEquals((string) $sxe['pClaimNumber'], $data['pClaimNumber']);
        $this->assertEquals((string) $sxe['pTrackingNumber'], $data['pTrackingNumber']);
        $this->assertEquals((string) $sxe['pPatientType'], $data['pPatientType']);
        $this->assertEquals((string) $sxe['pIsEmergency'], $data['pIsEmergency']);

        /**
         * @todo More tests in non-shallow JSON elements
         */
    }
}

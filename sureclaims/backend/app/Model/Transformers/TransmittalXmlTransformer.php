<?php

/**
 * TransmittalXmlTransformer.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Model\Transformers;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Model\Entities\Claim;
use League\Fractal\TransformerAbstract;
use App\Model\Entities\Transmittal;

/**
 * Class TransmittalXmlTransformer.
 *
 * @package namespace App\Model\Transformers;
 */
class TransmittalXmlTransformer extends TransformerAbstract
{
    /**
     * Transform the Transmittal entity.
     *
     * @param \App\Model\Entities\Transmittal $transmittal
     *
     * @return array
     */
    public function transform(Transmittal $transmittal)
    {
        if (empty($transmittal->data_json)) {
            return [
                'id' => (int) $transmittal->id,
                'xml' => null,
            ];
        }
        return [
            'id' => (int) $transmittal->id,
            'xml' => $this->xmlize($transmittal),
        ];
    }

    /**
     * @param Transmittal|null $transmittal
     *
     * @return string
     *
     * @throws
     */
    public function xmlize(Transmittal $transmittal = null) : string
    {
        $rootElement = 'eCLAIMS';

        $eClaimsService = app(EclaimsServiceInterface::class);
        $credentials = $eClaimsService->credentials();

        $xml = new \SimpleXMLElement("<{$rootElement}></{$rootElement}>");
        $xml->addAttribute('pUserName', $credentials['pUserName']);
        $xml->addAttribute('pUserPassword', $credentials['pUserPassword']);
        $xml->addAttribute('pHospitalCode', $credentials['pHospitalCode']);
        $xml->addAttribute('pHospitalEmail', $credentials['pHospitalEmail']);
        $xml->addAttribute('pServiceProvider', $credentials['pServiceProvider']);
//        $xml->addAttribute('pCertificateId', $credentials['pCertificateId']);

        $eTransmittal = $xml->addChild('eTRANSMITTAL');
        $eTransmittal->addAttribute('pHospitalTransmittalNo', $transmittal->transmittal_no);
        $eTransmittal->addAttribute('pTotalClaims', $transmittal->claims->count());

        $dom = dom_import_simplexml($xml)->ownerDocument;

        $fragment = $dom->createDocumentFragment();
        foreach ($transmittal->claims as $claim) {
            /** @var Claim $claim */
            $xml = preg_replace("/\<\?xml.*\>\s+/", '', $claim->data_xml) . "";
            $fragment->appendXML($xml);
        }

        $eTransmittalDom = $dom->getElementsByTagName('eTRANSMITTAL')->item(0);
        if ($fragment->hasChildNodes()) {
            $eTransmittalDom->appendChild($fragment);
        }

        $dom->formatOutput = true;
        return $dom->saveXML();
    }
}

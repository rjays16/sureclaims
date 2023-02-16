<?php

/**
 * EClaimsService.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Lib\Soap;

use App\Lib\Soap\Exceptions\InvalidXMLException;
use Artisaninweb\SoapWrapper\Service;
use Artisaninweb\SoapWrapper\SoapWrapper;

/**
 *
 * Description of EClaimsService
 *
 */
class EClaimsService implements EclaimsServiceInterface
{
    /** @var SoapWrapper */
    private $soapWrapper;
    private $username;
    private $password;
    private $hospitalCode;

    /**
     * EClaimsService constructor.
     */
    public function __construct()
    {
        $this->soapWrapper = app(SoapWrapper::class);
        try {
            $this->soapWrapper->add('PHIC', function ($service) {
                /** @var Service $service */

                $opts = [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false
                    ],
                ];

                // SOAP 1.2 client
                $params = [
                    'encoding' => 'UTF-8',
                    'verifypeer' => false,
                    'verifyhost' => false,
                    'soap_version' => SOAP_1_2,
                    'exceptions' => 1,
                    'connection_timeout' => 180,
                    'stream_context' => stream_context_create($opts)
                ];

                $service->wsdl(__DIR__ . '/eclaims.wsdl')
                    ->options($params)
                    ->trace(true)
                    ->cache(WSDL_CACHE_NONE);
            });
        } catch (\Exception $e) {
            // Ignore exceptions
        }
    }

    /**
     * @param string $pMemberLastName
     * @param string $pMemberFirstName
     * @param string $pMemberMiddleName
     * @param string $pMemberSuffix
     * @param string $pMemberBirthDate
     */
    public function getMemberPin(
        $pMemberLastName,
        $pMemberFirstName,
        $pMemberMiddleName,
        $pMemberSuffix,
        $pMemberBirthDate
    ) : string
    {
        $maxExecutionTime = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);
        $response = $this->soapWrapper->call('PHIC.GetMemberPIN', $this->credentials() + [
            'pMemberLastName' => $pMemberLastName,
            'pMemberFirstName' => $pMemberFirstName,
            'pMemberMiddleName' => $pMemberMiddleName,
            'pMemberSuffix' => $pMemberSuffix,
            'pMemberBirthDate' => $pMemberBirthDate,
        ]);

        ini_set('max_execution_time', $maxExecutionTime);
        return $response;
    }

    /**
     * @param array $parameters
     *
     * @return string
     *
     * @throws \Exception
     */
    public function isClaimEligible(array $parameters) : string
    {
        $maxExecutionTime = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);
        $response = $this->soapWrapper->call('PHIC.isClaimEligible', $this->credentials() + [
            'pPIN' => $parameters['pPIN'],
            'pMemberLastName' => $parameters['pMemberLastName'],
            'pMemberFirstName' => $parameters['pMemberFirstName'],
            'pMemberMiddleName' => $parameters['pMemberMiddleName'],
            'pMemberSuffix' => $parameters['pMemberSuffix'],
            'pMemberBirthDate' => $parameters['pMemberBirthDate'],
            'pMailingAddress' => $parameters['pMailingAddress'],
            'pZipCode' => $parameters['pZipCode'],
            'pPatientIs' => $parameters['pPatientIs'],
            'pAdmissionDate' => $parameters['pAdmissionDate'],
            'pDischargeDate' => $parameters['pDischargeDate'],
            'pPatientLastName' => $parameters['pPatientLastName'],
            'pPatientFirstName' => $parameters['pPatientFirstName'],
            'pPatientMiddleName' => $parameters['pPatientMiddleName'],
            'pPatientSuffix' => $parameters['pPatientSuffix'],
            'pPatientBirthDate' => $parameters['pPatientBirthDate'],
            'pPatientGender' => $parameters['pPatientGender'],
            'pMemberShipType' => $parameters['pMemberShipType'],
            'pPEN' => $parameters['pPEN'],
            'pEmployerName' => $parameters['pEmployerName'],
            'pRVS' => $parameters['pRVS'],
            'pTotalAmountActual' => $parameters['pTotalAmountActual'],
            'pTotalAmountClaimed' => $parameters['pTotalAmountClaimed'],
            'pIsFinal' => $parameters['pIsFinal'],
        ]);

        ini_set('max_execution_time', $maxExecutionTime);
        return $response;
    }

    /**
     * @param array $parameters
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getDoctorPAN(array $parameters) : string
    {
        $maxExecutionTime = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);
        $response = $this->soapWrapper->call('PHIC.GetDoctorPAN', $this->credentials() + [
            'pDoctorTIN' => $parameters['pDoctorTIN'],
            'pDoctorLastName' => $parameters['pDoctorLastName'],
            'pDoctorFirstName' => $parameters['pDoctorFirstName'],
            'pDoctorMiddleName' => $parameters['pDoctorMiddleName'],
            'pDoctorSuffix' => $parameters['pDoctorSuffix'],
            'pDoctorBirthDate' => $parameters['pDoctorBirthDate'],
        ]);

        ini_set('max_execution_time', $maxExecutionTime);
        return $response;
    }

    /**
     * @param string $xml
     * @return string
     */
    public function eClaimsUpload($xml): string
    {
        $maxExecutionTime = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);
        $response = $this->soapWrapper->call('PHIC.eClaimsUpload', $this->credentials() + [
            'pXML' => $xml,
        ]);

        ini_set('max_execution_time', $maxExecutionTime);
        return $response;
    }

    /**
     * @param string $sourceFilePath
     * @param string $destFilePath
     * @param string $mimeType
     */
    public function encryptDocument($sourceFilePath, $destFilePath, $mimeType = 'application/pdf') : void
    {
        $encryptor = new PhilHealthEClaimsEncryptor();
        $encryptor->encryptImageFile($sourceFilePath, $mimeType, $destFilePath);
    }

    /**
     * @param string $xml
     *
     * @throws InvalidXMLException
     */
    public function validateTransmittalXML($xml)
    {
        $dtdPath = 'file:///' . implode('/', explode('\\', realpath(__DIR__.('/eClaimsDef.dtd'))));
        $xml = preg_replace('/\<\?xml version\=\"1.0\"\?\>/', '', $xml);
        $docType = "<?xml version=\"1.0\"?>\n<!DOCTYPE eCLAIMS PUBLIC \"eclaims\" \"{$dtdPath}\">\n";
        $xml = $docType . $xml;
        $old = new \DOMDocument();
        $old->loadXML(trim($xml));

        $document = new DOMDocument($old);
        if (!$document->validate()) {
            throw new InvalidXMLException($document->errors);
        }
    }

    /**
     * @param string $xml
     *
     * @throws InvalidXMLException
     */
    public function validateClaimXML($xml)
    {
        $dtdPath = 'file:///' . implode('/', explode('\\', realpath(__DIR__.('/eClaimsDef.dtd'))));
        $xml = preg_replace('/\<\?xml version\=\"1.0\"\?\>/', '', $xml);
        $docType = "<?xml version=\"1.0\"?>\n<!DOCTYPE CLAIM PUBLIC \"eclaims\" \"{$dtdPath}\">\n";
        $xml = $docType . $xml;
        $old = new \DOMDocument();
        $old->loadXML(trim($xml));

        $document = new DOMDocument($old);
        if (!$document->validate()) {
            throw new InvalidXMLException($document->errors);
        }
    }

    /**
     * @return array
     */
    public function credentials() : array
    {
        return [
            'pUserName' => config('eclaims.username'),
            'pUserPassword' => config('eclaims.password'),
            'pHospitalCode' => config('eclaims.hospitalCode'),
        ];
    }

    /**
     * @param string $pPen
     * @param string $pEmployerName
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function searchEmployer( ?string $pPen = "", ?string $pEmployerName = "" )
    {
        $maxExecutionTime = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);
        $response = $this->soapWrapper->call('PHIC.SearchEmployer', $this->credentials() + [
            'pPen' => $pPen,
            'pEmployerName' => "%{$pEmployerName}%",
        ]);

        ini_set('max_execution_time', $maxExecutionTime);
        return $response;
    }

    /**
     * @param array $pSeriesLhioNos can be separated by commas
     * @return mixed
     */
    public function getClaimStatus( array $pSeriesLhioNos )
    {
        $maxExecutionTime = ini_get( 'max_execution_time' );
        ini_set( 'max_execution_time', 0 );
        $response = $this->soapWrapper->call( 'PHIC.GetClaimStatus', $this->credentials() + [
                'pSeriesLhioNos' => implode( ',', $pSeriesLhioNos )
            ] );

        ini_set( 'max_execution_time', $maxExecutionTime );
        return $response;
    }

    /**
     * @param string $pReceiptTicketNumber
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getUploadedClaimsMap( $pReceiptTicketNumber ): array
    {
        // TODO: Implement getUploadedClaimsMap() method.
        return [];
    }

    /**\
     * @param array $data
     * @return mixed
     */
    public function documentUpload( array $data )
    {
        // TODO: Implement documentUpload() method.
        return [];
    }
}

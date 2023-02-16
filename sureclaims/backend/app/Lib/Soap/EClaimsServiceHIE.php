<?php

/**
 * EClaimsServiceHIE.php
 *
 * @author        Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Lib\Soap;

use App\Lib\Soap\Exceptions\PhilhealthException;
use App\Lib\Soap\Exceptions\InvalidXMLException;
use GuzzleHttp\Client;
use Log;
use Psr\Http\Message\ResponseInterface;

/**
 *
 * Description of EClaimsServiceHIE
 *
 */
class EClaimsServiceHIE implements EclaimsServiceInterface
{
    private $client;
    
    /**
     * EClaimsService constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri'    => config('eclaims.hie_api_url'),
            'http_errors' => false,
        ]);
    }
    
    /**
     * @param  string  $pMemberLastName
     * @param  string  $pMemberFirstName
     * @param  string  $pMemberMiddleName
     * @param  string  $pMemberSuffix
     * @param  string  $pMemberBirthDate
     *
     * @throws \Exception
     */
    public function getMemberPin(
        $pMemberLastName,
        $pMemberFirstName,
        $pMemberMiddleName,
        $pMemberSuffix,
        $pMemberBirthDate
    ): string {
        ini_set('max_execution_time', 0);
        
        $response = $this->callService(
            'hie/eligibility/getPIN',
            $this->credentials()+[
                'pMemberLastName'   => $pMemberLastName,
                'pMemberFirstName'  => $pMemberFirstName,
                'pMemberMiddleName' => $pMemberMiddleName,
                'pMemberSuffix'     => $pMemberSuffix,
                'pMemberBirthDate'  => $pMemberBirthDate,
            ]
        );
        
        $result = $this->processResponse($response);
        
        return $result;
    }
    
    /**
     * @param  array  $parameters
     *
     * @return string
     *
     * @throws \Exception
     */
    public function isClaimEligible(array $parameters): array
    {
        ini_set('max_execution_time', 0);
        $response = $this->callService(
            'hie/eligibility/check',
            $this->credentials()+[
                'pPIN'                => $parameters['pPIN'],
                'pMemberLastName'     => $parameters['pMemberLastName'],
                'pMemberFirstName'    => $parameters['pMemberFirstName'],
                'pMemberMiddleName'   => $parameters['pMemberMiddleName'],
                'pMemberSuffix'       => $parameters['pMemberSuffix'],
                'pMemberBirthDate'    => $parameters['pMemberBirthDate'],
                'pMailingAddress'     => $parameters['pMailingAddress'],
                'pZipCode'            => $parameters['pZipCode'],
                'pPatientIs'          => $parameters['pPatientIs'],
                'pAdmissionDate'      => $parameters['pAdmissionDate'],
                'pDischargeDate'      => $parameters['pDischargeDate'],
                'pPatientLastName'    => $parameters['pPatientLastName'],
                'pPatientFirstName'   => $parameters['pPatientFirstName'],
                'pPatientMiddleName'  => $parameters['pPatientMiddleName'],
                'pPatientSuffix'      => $parameters['pPatientSuffix'],
                'pPatientBirthDate'   => $parameters['pPatientBirthDate'],
                'pPatientGender'      => $parameters['pPatientGender'],
                'pMemberShipType'     => $parameters['pMemberShipType'],
                'pPEN'                => $parameters['pPEN'],
                'pEmployerName'       => $parameters['pEmployerName'],
                'pRVS'                => $parameters['pRVS'],
                'pTotalAmountActual'  => $parameters['pTotalAmountActual'],
                'pTotalAmountClaimed' => $parameters['pTotalAmountClaimed'],
                'pIsFinal'            => $parameters['pIsFinal'],
            ]
        );
        
        $result = $this->processResponse($response);
        
        return $result;
        
    }
    
    /**
     * @param  array  $parameters
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getDoctorPAN(array $parameters): string
    {
        ini_set('max_execution_time', 0);
        $response = $this->callService(
            'hie/doctor/getPan',
            $this->credentials()+[
                'pDoctorTIN'        => $parameters['pDoctorTIN'],
                'pDoctorLastName'   => $parameters['pDoctorLastName'],
                'pDoctorFirstName'  => $parameters['pDoctorFirstName'],
                'pDoctorMiddleName' => $parameters['pDoctorMiddleName'],
                'pDoctorSuffix'     => $parameters['pDoctorSuffix'],
                'pDoctorBirthDate'  => $parameters['pDoctorBirthDate'],
            ]
        );
        
        $result = $this->processResponse($response);
        
        return $result;
    }
    
    /**
     * @param  string  $xml
     *
     * @return array
     *
     * @throws \Exception
     */
    public function eClaimsUpload($xml): array
    {
        ini_set('max_execution_time', 0);
        
        $response = $this->callService('hie/transmittal/upload',
            $this->credentials()+[
                'pXML' => $xml,
            ],
            'POST'
        );
        
        $result = $this->processResponse($response);
        
        return $result;
    }
    
    /**
     * @param  string  $pReceiptTicketNumber
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getUploadedClaimsMap($pReceiptTicketNumber): array
    {
        ini_set('max_execution_time', 0);
        
        $response = $this->callService('hie/transmittal/claimsMap',
            $this->credentials()+[
                'pReceiptTicketNumber' => $pReceiptTicketNumber,
            ]
        );
        
        $result = $this->processResponse($response);
        
        return $result;
    }
    
    /**
     * @param $claimSeries
     * @param $xml  string
     *
     * @return array
     *
     * @throws \Exception
     */
    public function addDocument($claimSeries, $xml): array
    {
        ini_set('max_execution_time', 0);
        $endpoint = 'hie/document/AddDocumentBackup';

        $timestamp = '';
        $response  = $this->callService(
            $endpoint,
            [
                // Request Headers
                'headers'       => [
                    'Request-Timestamp' => $timestamp,
                    'AUTHORIZATION'     => $this->authorization(
                        $this->signature('POST', $endpoint, $timestamp)
                    ),
                ],
                'xmlData'       => $xml,
                'pHospitalCode' => config('eclaims.hospital_code', '950102'),
                'pSeriesLhioNo' => $claimSeries,
            ],
            'POST'
        );
        $result    = $this->processResponse($response);

        return $result;
    }
    
    
    /**
     * @param  string  $pPen
     * @param  string  $pEmployerName
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function searchEmployer(?string $pPen = "", ?string $pEmployerName = "")
    {
        ini_set('max_execution_time', 0);
        
        $response = $this->callService(
            'hie/eligibility/searchEmployer',
            $this->credentials()+[
                'pPEN'          => $pPen,
                'pEmployerName' => "%{$pEmployerName}%",
            ]
        );
        
        $result = $this->processResponse($response);
        
        return $result;
    }
    
    /**
     * @param  array  $pSeriesLhioNos  can be separated by commas
     *
     * @return mixed
     * @throws \Exception
     */
    public function getClaimStatus(array $pSeriesLhioNos)
    {
        ini_set('max_execution_time', 0);
        
        $params = $this->credentials()+[
                'pSeriesLhioNos' => implode(',', $pSeriesLhioNos),
            ];
        \Log::info(['getClaimStatus::$params', $params]);
        $response = $this->callService('hie/claim/status', $params);
        
        $result = $this->processResponse($response);
        
        return $result;
    }
    
    public function documentUpload(array $data)
    {
        ini_set('max_execution_time', 0);
        
        $endpoint  = 'hie/document/upload';
        $timestamp = '';
        
        $response = $this->callService(
            $endpoint,
            [
                // Request Headers
                'headers'   => [
                    'Request-Timestamp' => $timestamp,
                    'AUTHORIZATION'     => $this->authorization(
                        $this->signature('POST', $endpoint, $timestamp)
                    ),
                ],
                'multipart' => array_merge(
                    $this->asMultipart($this->credentials()),
                    $this->asMultipart([
                        'name'        => array_get($data, 'name'),
                        'owner'       => array_get($data, 'owner'),
                        'contentType' => array_get($data, 'contentType'),
                    ]),
                    [
                        [
                            'name'     => 'attachment',
                            'filename' => array_get($data, 'name'),
                            'contents' => array_get($data, 'attachment'),
                        ],
                    ]
                ),
            ],
            'POST',
            true
        );
        $result   = $this->processResponse($response);
        
        return $result;
    }
    
    /**
     * @param  string  $sourceFilePath
     * @param  string  $destFilePath
     * @param  string  $mimeType
     */
    public function encryptDocument($sourceFilePath, $destFilePath, $mimeType = 'application/pdf'): void
    {
        $encryptor = new PhilHealthEClaimsEncryptor();
        $encryptor->encryptImageFile($sourceFilePath, $mimeType, $destFilePath);
    }
    
    /**
     * @param  string  $xml
     *
     * @throws InvalidXMLException
     */
    public function validateTransmittalXML($xml)
    {
        $dtdPath = 'file:///' . implode('/', explode('\\', realpath(__DIR__ . ('/eClaimsDef.dtd'))));
        $xml     = preg_replace('/\<\?xml version\=\"1.0\"\?\>/', '', $xml);
        $docType = "<?xml version=\"1.0\"?>\n<!DOCTYPE eCLAIMS PUBLIC \"eclaims\" \"{$dtdPath}\">\n";
        $xml     = $docType . $xml;
        $old     = new \DOMDocument();
        $old->loadXML(trim($xml));
        
        $document = new DOMDocument($old);
        if (!$document->validate()) {
            throw new InvalidXMLException($document->errors);
        }
    }
    
    /**
     * @param  string  $xml
     *
     * @throws InvalidXMLException
     */
    public function validateClaimXML($xml)
    {
        $dtdPath = 'file:///' . implode('/', explode('\\', realpath(__DIR__ . ('/eClaimsDef.dtd'))));
        $xml     = preg_replace('/\<\?xml version\=\"1.0\"\?\>/', '', $xml);
        $docType = "<?xml version=\"1.0\"?>\n<!DOCTYPE CLAIM PUBLIC \"eclaims\" \"{$dtdPath}\">\n";
        $xml     = $docType . $xml;
        $old     = new \DOMDocument();
        $old->loadXML(trim($xml));
        
        $document = new DOMDocument($old);
        if (!$document->validate()) {
            throw new InvalidXMLException($document->errors);
        }
    }
    
    /**
     * @return array
     */
    public function credentials(): array
    {
        return [
            'pUserName'        => config('eclaims.username', ''),
            'pUserPassword'    => config('eclaims.password', ''),
            'pHospitalCode'    => config('eclaims.hospital_code', '950102'),
            'pHospitalEmail'   => config('eclaims.hospital_email', ''),
            'pServiceProvider' => config('eclaims.service_provider', ''),
            'pCertificateId'   => config('eclaims.certificate_id', ''),
        ];
    }
    
    public function asMultipart(?array $list = []): array
    {
        $new = [];
        foreach ($list as $key => $value) {
            $new[] = [
                'name'     => $key,
                'contents' => $value,
            ];
        }
        
        return $new;
    }
    
    /**
     * @param $id
     *
     * @return String
     */
    public function documentURL($id)
    {
        return url(config('eclaims.hie_file_url') . $id . '.html');
    }
    
    public function signature(string $method, string $endpoint, string $timestamp): string
    {
        $dateOfRequest  = $timestamp;
        $contentMD5     = '';
        $dataToBeSigned = strtoupper($method) . "\n" .
            $dateOfRequest . "\n" .
            $contentMD5 . "\n" .
            strtolower($endpoint);
        
        return hash_hmac("sha1", utf8_encode($dataToBeSigned), config('eclaims.hie_client_secret'));
    }
    
    private function authorization(string $signature): string
    {
        return 'TRACERWS ' . config('eclaims.hie_client_id') . ":" . $signature;
    }
    
    /**
     * @param  string  $uri
     * @param  array   $parameters
     * @param  string  $method
     *
     * @return ResponseInterface
     */
    private function callService(string $uri, $parameters = [], $method = 'GET', $multipart = false): ResponseInterface
    {
        $options = [];
        
        $options['headers'] = array_get($parameters, 'headers');
        array_forget($parameters, 'headers');
        
        if ($method==='POST') {
            if ($multipart) {
                $options['multipart'] = array_get($parameters, 'multipart');
            } else {
                $options['form_params'] = $parameters;
            }
        } else {
            $options['query'] = $parameters;
        }
        
        Log::info('Calling HIE Service: ' . $uri);
        
        // Log::info($options);
        return $this->client->request($method, $uri, $options);
    }
    
    
    /**
     * @param  ResponseInterface  $response
     *
     * @return mixed
     * @throws \Exception
     *
     */
    private function processResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode()===404) {
            throw new \Exception('HIE service endpoint not found');
        }
        
        $result = json_decode($response->getBody(), true);
        
        if (empty($result)) {
            Log::error('Non-JSON content from HIE Service', [
                'body' => $response->getBody(),
            ]);
            throw new \Exception('Invalid message format returned from HIE server');
        }
        
        if (@$result['success']) {
            Log::info('Service call successful', $result);
            
            return $result['data'];
        }
        
        Log::error('Service call failed', $result);
        
        if (array_get($result, 'data.code')=='PhilhealthException') {
            throw new PhilhealthException($result, @$result['message'] ?: 'Error');
        }
        
        throw new \Exception(@$result['message'] ?: 'Error');
    }
    
}

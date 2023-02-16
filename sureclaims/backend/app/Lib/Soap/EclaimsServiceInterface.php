<?php

/**
 * EclaimsServiceInterface.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Lib\Soap;

use App\Lib\Soap\Exceptions\InvalidXMLException;

/**
 *
 * Description of EclaimsServiceInterface
 *
 */
interface EclaimsServiceInterface
{

    /**
     * @return array
     */
    public function credentials(): array;

    /**
     * @param string $xml
     *
     * @throws InvalidXMLException
     */
    public function validateTransmittalXML( $xml );

    /**
     * @param string $xml
     *
     * @throws InvalidXMLException
     */
    public function validateClaimXML($xml);

    /**
     * @param string $pMemberLastName
     * @param string $pMemberFirstName
     * @param string $pMemberMiddleName
     * @param string $pMemberSuffix
     * @param string $pMemberBirthDate
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getMemberPin(
        $pMemberLastName,
        $pMemberFirstName,
        $pMemberMiddleName,
        $pMemberSuffix,
        $pMemberBirthDate
    );

    /**
     * @param array $parameters
     *
     * @return string
     *
     * @throws \Exception
     */
    public function isClaimEligible( array $parameters );

    /**
     * @param string $xml
     *
     * @return array
     */
    public function eClaimsUpload( $xml ) : array;

    /**
     * @param string $pReceiptTicketNumber
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getUploadedClaimsMap($pReceiptTicketNumber) : array;

    /**
     * @param string $sourceFilePath
     * @param string $destFilePath
     * @param string $mimeType
     */
    public function encryptDocument($sourceFilePath, $destFilePath, $mimeType = 'application/pdf') : void;

    /**
     * @param string $pPen
     * @param string $pEmployerName
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function searchEmployer( ?string $pPen = "", ?string $pEmployerName = "" );

    /**
     * @param array $pSeriesLhioNos can be separated by commas
     * @return mixed
     */
    public function getClaimStatus( array $pSeriesLhioNos );


    /**\
     * @param array $data
     * @return mixed
     */
    public function documentUpload(array $data);

}

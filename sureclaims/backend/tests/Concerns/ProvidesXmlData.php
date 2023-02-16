<?php

/**
 * ProvidesXmlData.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Concerns;

/**
 *
 * Description of ProvidesXmlData
 *
 */

trait ProvidesXmlData
{

    /**
     * @return string
     */
    public function provideTransmittalWrapperXml() : string
    {
        return @file_get_contents(realpath($this->xmlResourcePath().'/transmittal-wrapper.xml'));
    }

    /**
     * @return string
     */
    public function providePhicClaimXml() : string
    {
        return @file_get_contents(realpath($this->xmlResourcePath().'/claim-from-phic.xml'));
    }

    /**
     * @return string
     */
    public function providePhicTransmittalXml() : string
    {
        return @file_get_contents(realpath($this->xmlResourcePath().'/transmittal-from-phic.xml'));
    }


    /**
     * @return string
     */
    private function xmlResourcePath() : string
    {
        return base_path('tests/resources/xml');
    }
}

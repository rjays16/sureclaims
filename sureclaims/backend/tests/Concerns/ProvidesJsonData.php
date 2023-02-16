<?php

/**
 * ProvidesJsonData.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Concerns;

/**
 *
 * Description of ProvidesJsonData
 *
 */

trait ProvidesJsonData
{

    /**
     * @return string
     */
    public function provideClaimJson() : string
    {
        return @file_get_contents(realpath($this->jsonResourcePath().'/claim.json'));
    }

    /**
     * @return string
     */
    public function provideClaimJsonWithRepeatingIcdCodes() : string
    {
        return @file_get_contents(realpath($this->jsonResourcePath().'/claim-with-repeating-icd-codes.json'));
    }

    /**
     * @return string
     */
    public function provideClaimJsonForSanitizationCheck() : string
    {
        return @file_get_contents(realpath($this->jsonResourcePath().'/claim-sanitization-check.json'));
    }

    /**
     * @return string
     */
    private function jsonResourcePath() : string
    {
        return base_path('tests/resources/json');
    }
}

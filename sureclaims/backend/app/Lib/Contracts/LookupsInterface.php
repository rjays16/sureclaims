<?php

/**
 * LookupsInterface.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Lib\Contracts;

/**
 *
 * Description of LookupsInterface
 *
 */
interface LookupsInterface
{
    /**
     * @param string $domain
     *
     * @return array
     */
    public function types($domain = null);

    /**
     * @param string $domain
     * @param string $type
     *
     * @return array
     */
    public function value($domain = null, $type = null);
}

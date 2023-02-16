<?php

/**
 * Authentication.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Lib\Auth\Contracts;

/**
 *
 * Description of Authentication
 *
 */

interface Authentication
{

    /**
     * @param string $id
     *
     * @return array
     */
    public function user($id);
}

<?php

/**
 * ErrorForrmatter.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL;

use GraphQL\Error\Error;
use Exception;

/**
 *
 * Description of ErrorForrmatter
 *
 */

class ErrorFormatter
{
    /**
     * @param Error $e
     */
    public static function format(Error $e)
    {
    	if (app()->bound('sentry')) {
    		app('sentry')->captureException($e);
        }
        return \Folklore\GraphQL\GraphQL::formatError($e);
    }
}

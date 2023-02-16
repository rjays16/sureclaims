<?php

/**
 * InvalidXMLException.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Lib\Soap\Exceptions;
use Throwable;

/**
 *
 * Description of InvalidXMLException
 *
 */

class InvalidXMLException extends \Exception
{

    /** @var array  */
    private $validationErrors;

    /**
     * InvalidXMLException constructor.
     * @param array $validationErrors
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(array $validationErrors = [], $message = "", $code = 0, Throwable $previous = null)
    {
        $this->validationErrors = $validationErrors;
        parent::__construct($message = '', $code, $previous);
    }

    /**
     * @return array
     */
    public function validationErrors() : array
    {
        return $this->validationErrors;
    }
}

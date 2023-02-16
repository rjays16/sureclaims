<?php
/**
 * PhilhealthException.php
 *
 * @author Jolly Caralos <jadcaralos@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 */


namespace App\Lib\Soap\Exceptions;


use Throwable;

class PhilhealthException extends \Exception
{
    /** @var array  */
    private $result;

    /**
     * @param array $result
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($result = [], $message = "", $code = 0, Throwable $previous = null)
    {
        $this->result = $result;
        parent::__construct($message = '', $code, $previous);
    }

    /**
     * @return mixed
     */
    public function result($key = null, $default = null)
    {
        if (!$key) {
            return $this->result;
        }
        return array_get($this->result, $key);
    }

    /**
     * @return mixed
     */
    public function reason()
    {
        return $this->result('data.reason', 'Error Philhealth response.');
    }
}

<?php

/**
 * DOMDocument.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Lib\Soap;

use DOMDocument as BaseDOMDocument;

/**
 * Class DOMDocument
 * @package App\Lib\Soap
 *
 * @property array $errors
 * @method validate() : boolean
 */
class DOMDocument
{
    private $_delegate;
    private $_validationErrors;

    public function __construct(BaseDOMDocument $pDocument)
    {
        $this->_delegate = $pDocument;
        $this->_validationErrors = [];
    }

    /**
     * @param $pMethodName
     * @param $pArgs
     * @return bool|mixed
     */
    public function __call($pMethodName, $pArgs)
    {
        if ($pMethodName == "validate") {
            $eh = set_error_handler([$this, "onValidateError"]);
            $rv = $this->_delegate->validate();
            if ($eh) {
                set_error_handler($eh);
            }

            return $rv;
        } else {
            return call_user_func_array([$this->_delegate, $pMethodName], $pArgs);
        }
    }

    /**
     * @param $pMemberName
     * @return array
     */
    public function __get($pMemberName)
    {
        if ($pMemberName == "errors") {
            return $this->_validationErrors;
        } else {
            return $this->_delegate->$pMemberName;
        }
    }

    /**
     * @param $pMemberName
     * @param $pValue
     */
    public function __set($pMemberName, $pValue)
    {
        $this->_delegate->$pMemberName = $pValue;
    }

    /**
     * @param $pNo
     * @param $pString
     * @param null $pFile
     * @param null $pLine
     * @param null $pContext
     */
    public function onValidateError($pNo, $pString, $pFile = null, $pLine = null, $pContext = null)
    {
        $this->_validationErrors[] = preg_replace("/^.+: */", "", $pString);
    }
}

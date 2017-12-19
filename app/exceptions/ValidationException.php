<?php

use Phalcon\DI;

/**
 * Created by PhpStorm.
 * User: cl
 * Date: 17/11/3
 * Time: 09:29
 */
class ValidationException extends Exception
{
    private static $codes = [400];

    public function __construct($message, $code) {
        if (!in_array($code, self::$codes)) throw new Exception('The ValidationException code '.$code.' is not allowed', 500);
        parent::__construct($message, $code);
    }

    public function __toString() {
        return __CLASS__.':['.$this->code.']:'.$this->message.'\n';
    }

    public function __destruct()
    {
        $response = DI::getDefault()->get('response');

        switch ($this->getCode()) {
            case 400: $response->setStatusCode(400, "Wrong Param"); break;
            default : break;
        }

        $response->sendHeaders();
    }
}
<?php

use Phalcon\DI;

/**
 * Created by PhpStorm.
 * User: cl
 * Date: 17/11/3
 * Time: 09:29
 */
class DbException extends Exception
{
    private static $codes = [500];

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
            case 500: $response->setStatusCode(500, "Server Error!"); break;
            default : break;
        }

        $response->sendHeaders();
    }
}
<?php

namespace App\FrontApi\Exception;

/**
 * Class FrontApiException
 * @package App\FrontApi\Exception
 */
class FrontApiException extends \Exception
{

    /**
     * FrontApiException constructor.
     * @param $message
     * @param $code
     */
    public function __construct($message, $code = 401)
    {
        parent::__construct($message, $code);
    }

}
<?php

namespace App\FrontApi\Exception;

use Symfony\Component\HttpFoundation\Response;

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
    public function __construct($message, $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message, $code);
    }

}
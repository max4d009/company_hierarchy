<?php

namespace App\FrontApi\Dto\Response\V1;

use App\FrontApi\Dto\Response\BaseResponseDto;

/**
 * Class ErrorResponseDto
 */
class ErrorResponseDto extends BaseResponseDto
{
    private bool $success = false;
    private int $code;
    private string $message;

    public static function fetch(string $message, int $code)
    {
        $response = new self();
        $response->message = $message;
        $response->code = $code;
        return $response;
    }
}

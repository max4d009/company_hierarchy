<?php

namespace App\FrontApi\Dto\Response\V1;

use App\FrontApi\Dto\Response\BaseResponseDto;

/**
 * Class SuccessResponseDto
 */
class SuccessResponseDto extends BaseResponseDto
{

    private bool $success;

    public static function fetch(bool $success)
    {
        $response = new self();
        $response->success = $success;
        return $response;
    }
}

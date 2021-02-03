<?php

namespace App\FrontApi\Dto\Interfaces\Request;

/**
 * Interface CreateCategoryRequestInterface
 * @package App\FrontApi\Dto\Interfaces\Request
 */
interface CreateCategoryRequestInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}

<?php

namespace App\FrontApi\Dto\Request\V1;


use App\FrontApi\Dto\Interfaces\Request\GetEmployeesRequestInterface;
use App\FrontApi\Dto\Request\BaseRequestDto;

class GetEmployeesRequestDto extends BaseRequestDto implements GetEmployeesRequestInterface
{
    /**
     * @var int
     */
    private int $categoryId = 0;

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }


}

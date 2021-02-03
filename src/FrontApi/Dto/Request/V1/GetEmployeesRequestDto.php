<?php

namespace App\FrontApi\Dto\Request\V1;

use Symfony\Component\Validator\Constraints as Assert;
use App\FrontApi\Dto\Interfaces\Request\GetEmployeesRequestInterface;
use App\FrontApi\Dto\Request\BaseRequestDto;

class GetEmployeesRequestDto extends BaseRequestDto implements GetEmployeesRequestInterface
{
    /**
     * @var null|int
     * @Assert\PositiveOrZero()
     */
    private ?int $categoryId = null;

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * @param int|null $categoryId
     */
    public function setCategoryId(?int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }




}

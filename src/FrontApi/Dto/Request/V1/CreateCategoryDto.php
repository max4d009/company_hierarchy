<?php

namespace App\FrontApi\Dto\Request\V1;


use App\FrontApi\Dto\Interfaces\Request\CreateCategoryRequestInterface;
use App\FrontApi\Dto\Request\BaseRequestDto;

class CreateCategoryDto extends BaseRequestDto implements CreateCategoryRequestInterface
{
    /**
     * @var string
     */
    private string $name;
    /**
     * @var null|int
     */
    private ?int $parentCategoryId = null;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int|null
     */
    public function getParentCategoryId(): ?int
    {
        return $this->parentCategoryId;
    }

    /**
     * @param int|null $parentCategoryId
     */
    public function setParentCategoryId(?int $parentCategoryId): void
    {
        $this->parentCategoryId = $parentCategoryId;
    }


}

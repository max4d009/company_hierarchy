<?php

namespace App\FrontApi\Dto\Request\V1;

use App\FrontApi\Dto\Interfaces\Request\CreateCategoryRequestInterface;
use App\FrontApi\Dto\Request\BaseRequestDto;
use Symfony\Component\Validator\Constraints as Assert;


class CreateCategoryDto extends BaseRequestDto implements CreateCategoryRequestInterface
{
    /**
     * @var string
     * @Assert\Length(min="5", max="100")
     */
    private string $name;

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


}

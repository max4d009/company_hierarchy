<?php

namespace App\FrontApi\Dto\Request\V1;

use Symfony\Component\Validator\Constraints as Assert;
use App\FrontApi\Dto\Interfaces\Request\CreateEmployeeRequestInterface;
use App\FrontApi\Dto\Request\BaseRequestDto;

class CreateEmployeeDto extends BaseRequestDto implements CreateEmployeeRequestInterface
{

    /**
     * @var string
     * @Assert\Length(min="2", max="100")
     */
    private string $firstName;
    /**
     * @var string
     * @Assert\Length(min="2", max="100")
     */
    private string $lastName;
    /**
     * @var string
     * @Assert\Email()
     */
    private string $email;
    /**
     * @var int
     * @Assert\PositiveOrZero()
     */
    private int $categoryId;

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getParentEmail(): string
    {
        return $this->parentEmail;
    }

    /**
     * @param string $parentEmail
     */
    public function setParentEmail(string $parentEmail): void
    {
        $this->parentEmail = $parentEmail;
    }

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

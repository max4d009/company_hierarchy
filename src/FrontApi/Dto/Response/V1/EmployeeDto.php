<?php

namespace App\FrontApi\Dto\Response\V1;


use App\Entity\Employee;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

/**
 * EmployeeDto
 */
class EmployeeDto
{
    /**
     * @var int
     * @OA\Property(description="The unique identifier of the user.")
     * @Serializer\Groups({"employees_req"})
     */
    public int $id;
    /**
     * @OA\Property(type="string", maxLength=100)
     * @Serializer\Groups({"employees_req"})
     */
    private string $firstName;
    /**
     * @OA\Property(type="string", maxLength=100)
     * @Serializer\Groups({"employees_req"})
     */
    private string $lastName;
    /**
     * @OA\Property(type="email", maxLength=100)
     * @Serializer\Groups({"employees_req"})
     */
    private string $email;
    /**
     * @OA\Property(ref=@Model(type=CategoryDto::class))
     * @Serializer\Groups({"employees_req"})
     */
    private CategoryDto $category;


    /**
     * @param Employee $employee
     */
    public function fillFromEntity(Employee $employee)
    {
        $this->id = $employee->getId();
        $this->firstName = $employee->getFirstName();
        $this->lastName = $employee->getLastName();
        $this->email = $employee->getEmail();
        $categoryDto = new CategoryDto();
        $categoryDto->fillFromEntity($employee->getCategory());
        $this->category = $categoryDto;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

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
     * @return CategoryDto
     */
    public function getCategory(): CategoryDto
    {
        return $this->category;
    }

    /**
     * @param CategoryDto $category
     */
    public function setCategory(CategoryDto $category): void
    {
        $this->category = $category;
    }

}

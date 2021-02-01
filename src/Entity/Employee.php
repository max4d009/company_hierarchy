<?php

namespace App\Entity;

use App\Entity\Traits\IdEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Employee
 * @package App\Entity
 * @ORM\Table(indexes={})
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 * @UniqueEntity(
 *     fields={"email"}
 * )
 */
class Employee
{
    use IdEntity;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $firstName;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $lastName;
    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private string $email;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="employeeList")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private Category $category;

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
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }


}
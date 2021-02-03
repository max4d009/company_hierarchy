<?php

namespace App\Entity;

use App\Entity\Traits\IdEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * @package App\Entity
 * @ORM\Table(indexes={})
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    use IdEntity;

    /**
     * @ORM\Column(name="name", type="string", length=100)
     */
    private string $name;

    /**
     * @var ArrayCollection|Employee[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Employee", mappedBy="category", cascade={"persist"})
     */
    private $employeeList;

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
     * @return Employee[]|ArrayCollection
     */
    public function getEmployeeList()
    {
        return $this->employeeList;
    }

    /**
     * @param Employee[]|ArrayCollection $employeeList
     */
    public function setEmployeeList($employeeList): void
    {
        $this->employeeList = $employeeList;
    }



}
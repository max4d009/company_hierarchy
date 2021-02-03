<?php

namespace App\Entity;

use App\Entity\Traits\IdEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Employee
 * @package App\Entity
 * @ORM\Table(indexes={})
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 * @UniqueEntity(
 *     fields={"email"}
 * )
 * @Gedmo\Tree(type="nested")
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
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private int $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private int $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private int $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private Employee $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private ?Employee $parent;

    /**
     * @ORM\OneToMany(targetEntity="Employee", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private PersistentCollection $children;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $countAllEmployeesCache = 0;

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

    /**
     * @return int
     */
    public function getLft(): int
    {
        return $this->lft;
    }

    /**
     * @param int $lft
     */
    public function setLft(int $lft): void
    {
        $this->lft = $lft;
    }

    /**
     * @return int
     */
    public function getLvl(): int
    {
        return $this->lvl;
    }

    /**
     * @param int $lvl
     */
    public function setLvl(int $lvl): void
    {
        $this->lvl = $lvl;
    }

    /**
     * @return int
     */
    public function getRgt(): int
    {
        return $this->rgt;
    }

    /**
     * @param int $rgt
     */
    public function setRgt(int $rgt): void
    {
        $this->rgt = $rgt;
    }

    /**
     * @return Employee
     */
    public function getRoot(): Employee
    {
        return $this->root;
    }

    /**
     * @param Employee $root
     */
    public function setRoot(Employee $root): void
    {
        $this->root = $root;
    }

    /**
     * @return Employee|null
     */
    public function getParent(): ?Employee
    {
        return $this->parent;
    }

    /**
     * @param Employee|null $parent
     */
    public function setParent(?Employee $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return PersistentCollection
     */
    public function getChildren(): PersistentCollection
    {
        return $this->children;
    }

    /**
     * @param PersistentCollection $children
     */
    public function setChildren(PersistentCollection $children): void
    {
        $this->children = $children;
    }

    /**
     * @return int
     */
    public function getCountAllEmployeesCache(): int
    {
        return $this->countAllEmployeesCache;
    }

    /**
     * @param int $countAllEmployeesCache
     */
    public function setCountAllEmployeesCache(int $countAllEmployeesCache): void
    {
        $this->countAllEmployeesCache = $countAllEmployeesCache;
    }


}
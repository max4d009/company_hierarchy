<?php


namespace App\Entity;

use App\Entity\Traits\IdEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Category
 * @package App\Entity
 * @Gedmo\Tree(type="nested")
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
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    private Category $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private ?Category $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private PersistentCollection $children;


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
     * @return Category
     */
    public function getRoot(): Category
    {
        return $this->root;
    }

    /**
     * @param Category $root
     */
    public function setRoot(Category $root): void
    {
        $this->root = $root;
    }

    /**
     * @return null|Category
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }

    /**
     * @param null|Category $parent
     */
    public function setParent(?Category $parent): void
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
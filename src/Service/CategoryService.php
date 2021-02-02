<?php


namespace App\Service;


use App\Entity\Category;
use App\Entity\Employee;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use function Doctrine\ORM\QueryBuilder;

class CategoryService
{
    private CategoryRepository $categoryRepository;
    private EntityManagerInterface $em;

    public function __construct(CategoryRepository $categoryRepository, EntityManagerInterface $em)
    {
        $this->categoryRepository = $categoryRepository;
        $this->em = $em;
    }


    /**
     * Recalculate category.countAllEmployeesCache
     * @param Category $category
     * @param bool $operationAdd
     */
    public function cacheSubordinatesCountTree(Category $category, bool $operationAdd)
    {
        // todo: may be combined into a single query

        // Get all parent categories and current category as ids
        $parentsQb = $this->categoryRepository->getPathQueryBuilder($category);
        $parentsQb->select('node.id as categoryId');
        $result = $parentsQb->getQuery()->getArrayResult();
        $categoryIds = array_column($result, 'categoryId');

        // For all categories ids increment/decrement countAllEmployeesCache
        $qb = $this->em->createQueryBuilder();
        $qb->update('App:Category', 'c');

        if($operationAdd)
            $qb->set('c.countAllEmployeesCache', "(c.countAllEmployeesCache + 1)");
        else
            $qb->set('c.countAllEmployeesCache', "(c.countAllEmployeesCache - 1)");

        $qb->where($qb->expr()->in('c.id', $categoryIds));
        $qb->getQuery()->getResult();
    }
}
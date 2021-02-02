<?php

namespace App\EventListener;

use App\Entity\Employee;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;


/**
 * Class DatabaseActivitySubscriber
 * @package App\EventListener
 */
class DatabaseActivitySubscriber implements EventSubscriber
{

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
        ];
    }

    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;
    /**
     * @var CategoryService
     */
    private CategoryService $categoryService;

    /**
     * DatabaseActivitySubscriber constructor.
     * @param CategoryRepository $categoryRepository
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryRepository $categoryRepository, CategoryService $categoryService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryService = $categoryService;
    }


    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $obj = $args->getObject();
        // Recalculate category.countAllEmployeesCache
        if($obj instanceof Employee and $obj->getCategory()){
           $this->categoryService->cacheSubordinatesCountTree($obj->getCategory(), true);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args): void
    {
        $obj = $args->getObject();
        // Recalculate category.countAllEmployeesCache
        if($obj instanceof Employee and $obj->getCategory()){
            $this->categoryService->cacheSubordinatesCountTree($obj->getCategory(), false);
        }
    }


}
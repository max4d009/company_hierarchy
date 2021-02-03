<?php

namespace App\EventListener;

use App\Entity\Employee;
use App\Service\EmployeeService;
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
     * @var EmployeeService
     */
    private EmployeeService $employeeService;


    /**
     * DatabaseActivitySubscriber constructor.
     * @param EmployeeService $employeeService
     */
    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }


    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        $obj = $args->getObject();
        // Recalculate employee.countAllEmployeesCache
        if($obj instanceof Employee){
           $this->employeeService->cacheSubordinatesCountTree($obj, true);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args): void
    {
        $obj = $args->getObject();
        // Recalculate employee.countAllEmployeesCache
        if($obj instanceof Employee){
            $this->employeeService->cacheSubordinatesCountTree($obj, false);
        }
    }


}
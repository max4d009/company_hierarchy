<?php

namespace App\Service;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EmployeeService
 * @package App\Service
 */
class EmployeeService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var EmployeeRepository
     */
    private EmployeeRepository $employeeRepository;

    /**
     * EmployeeService constructor.
     * @param EntityManagerInterface $em
     * @param EmployeeRepository $employeeRepository
     */
    public function __construct(EntityManagerInterface $em, EmployeeRepository $employeeRepository)
    {
        $this->em = $em;
        $this->employeeRepository = $employeeRepository;
    }


    /**
     * Recalculate employee.countAllEmployeesCache
     * @param Employee $employee
     * @param bool $operationAdd
     */
    public function cacheSubordinatesCountTree(Employee $employee, bool $operationAdd)
    {
        // todo: may be combined into a single query

        // Get all parent employees and current employee as ids
        $parentsQb = $this->employeeRepository->getPathQueryBuilder($employee);
        $parentsQb->select('node.id as employeeId');
        $result = $parentsQb->getQuery()->getArrayResult();
        $employeeIds = array_column($result, 'employeeId');

        // For all employees ids increment/decrement countAllEmployeesCache
        $qb = $this->em->createQueryBuilder();
        $qb->update('App:Employee', 'e');

        if($operationAdd)
            $qb->set('e.countAllEmployeesCache', "(e.countAllEmployeesCache + 1)");
        else
            $qb->set('e.countAllEmployeesCache', "(e.countAllEmployeesCache - 1)");

        $qb->where($qb->expr()->in('e.id', $employeeIds));
        $qb->andWhere('e.id != :current')->setParameter('current', $employee->getId());
        $qb->getQuery()->getResult();
    }
}
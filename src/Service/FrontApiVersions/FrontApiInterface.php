<?php

namespace App\Service\FrontApiVersions;


use App\Entity\Category;
use App\Entity\Employee;
use App\FrontApi\Dto\Interfaces\Request\CreateEmployeeRequestInterface;
use App\FrontApi\Dto\Interfaces\Request\GetCategoriesRequestInterface;
use App\FrontApi\Dto\Interfaces\Request\GetEmployeesRequestInterface;
use App\FrontApi\Dto\Interfaces\Response\CreateEmployeeResponseInterface;
use App\FrontApi\Dto\Interfaces\Response\GetEmployeesResponseInterface;

/**
 * Interface FrontApiInterface
 */
interface FrontApiInterface
{
    /**
     * @return string
     */
    public function getApiVersion() : string;

    /**
     * @param CreateEmployeeRequestInterface $createEmployeeDto
     * @return CreateEmployeeResponseInterface
     */
    public function createEmployee(CreateEmployeeRequestInterface $createEmployeeDto) : CreateEmployeeResponseInterface;

    /**
     * @param GetCategoriesRequestInterface $createEmployeeDto
     * @return Category[]
     */
    public function getCategories(GetCategoriesRequestInterface $createEmployeeDto) : array;

    /**
     * @param GetEmployeesRequestInterface $createEmployeeDto
     * @return Employee[]
     */
    public function getEmployees(GetEmployeesRequestInterface $createEmployeeDto) : array;

}

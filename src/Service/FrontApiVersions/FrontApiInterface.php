<?php

namespace App\Service\FrontApiVersions;


use App\Entity\Category;
use App\Entity\Employee;
use App\FrontApi\Dto\Interfaces\Request\CreateCategoryRequestInterface;
use App\FrontApi\Dto\Interfaces\Request\CreateEmployeeRequestInterface;
use App\FrontApi\Dto\Interfaces\Request\GetCategoriesRequestInterface;
use App\FrontApi\Dto\Interfaces\Request\GetEmployeesRequestInterface;

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
     * @return bool
     */
    public function createEmployee(CreateEmployeeRequestInterface $createEmployeeDto) : bool;

    /**
     * @param CreateCategoryRequestInterface $createCategoryDto
     * @return bool
     */
    public function createCategory(CreateCategoryRequestInterface $createCategoryDto) : bool;

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

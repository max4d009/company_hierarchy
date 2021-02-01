<?php

namespace App\Service\FrontApiVersions;

use App\FrontApi\Dto\Interfaces\Request\CreateEmployeeRequestInterface;
use App\FrontApi\Dto\Interfaces\Request\GetCategoriesRequestInterface;
use App\FrontApi\Dto\Interfaces\Request\GetEmployeesRequestInterface;
use App\FrontApi\Dto\Interfaces\Response\CreateEmployeeResponseInterface;
use App\Repository\CategoryRepository;
use App\Repository\EmployeeRepository;

class FrontApiV1Service implements FrontApiInterface
{
    private CategoryRepository $categoryRepository;
    private EmployeeRepository $employeeRepository;

    /**
     * FrontApiV1Service constructor.
     * @param CategoryRepository $categoryRepository
     * @param EmployeeRepository $employeeRepository
     */
    public function __construct(CategoryRepository $categoryRepository, EmployeeRepository $employeeRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->employeeRepository = $employeeRepository;
    }


    /**
     * @inheritDoc
     */
    public function getApiVersion(): string
    {
        return FrontApiVersionsEnum::V1;
    }

    /**
     * @inheritDoc
     */
    public function createEmployee(CreateEmployeeRequestInterface $createEmployeeDto): CreateEmployeeResponseInterface
    {
        // TODO: Implement createEmployee() method.
    }

    /**
     * @inheritDoc
     */
    public function getCategories(GetCategoriesRequestInterface $createEmployeeDto): array
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * @inheritDoc
     */
    public function getEmployees(GetEmployeesRequestInterface $createEmployeeDto): array
    {
        return $this->employeeRepository->findAll();
    }
}



<?php

namespace App\Service\FrontApiVersions;

use App\Entity\Category;
use App\Entity\Employee;
use App\FrontApi\Dto\Interfaces\Request\CreateCategoryRequestInterface;
use App\FrontApi\Dto\Interfaces\Request\CreateEmployeeRequestInterface;
use App\FrontApi\Dto\Interfaces\Request\GetCategoriesRequestInterface;
use App\FrontApi\Dto\Interfaces\Request\GetEmployeesRequestInterface;
use App\FrontApi\Exception\FrontApiException;
use App\Repository\CategoryRepository;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;

class FrontApiV1Service implements FrontApiInterface
{
    private CategoryRepository $categoryRepository;
    private EmployeeRepository $employeeRepository;
    private EntityManagerInterface $em;

    /**
     * FrontApiV1Service constructor.
     * @param CategoryRepository $categoryRepository
     * @param EmployeeRepository $employeeRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(CategoryRepository $categoryRepository, EmployeeRepository $employeeRepository, EntityManagerInterface $em)
    {
        $this->categoryRepository = $categoryRepository;
        $this->employeeRepository = $employeeRepository;
        $this->em = $em;
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
     * @throws FrontApiException
     */
    public function createEmployee(CreateEmployeeRequestInterface $createEmployeeDto): bool
    {
        $parent = $this->employeeRepository->findOneBy(['email'=>$createEmployeeDto->getParentEmail()]);
        if(!$parent){
            throw new FrontApiException("Employee with email '{$createEmployeeDto->getEmail()}' doesnt exist");
        }
        if($this->employeeRepository->findOneBy(['email'=>$createEmployeeDto->getEmail()])){
            throw new FrontApiException('Employee with this email exists');
        }
        $category = $this->categoryRepository->find($createEmployeeDto->getCategoryId());
        if(!$category){
            throw new FrontApiException("Category with '{$createEmployeeDto->getCategoryId()}' doesnt exist");
        }
        $employee = new Employee();
        $employee->setEmail($createEmployeeDto->getEmail());
        $employee->setFirstName($createEmployeeDto->getFirstName());
        $employee->setLastName($createEmployeeDto->getLastName());
        $employee->setCategory($category);
        $this->em->persist($employee);
        $this->em->flush();
        return true;
    }

    /**
     * @inheritDoc
     * @throws FrontApiException
     */
    public function createCategory(CreateCategoryRequestInterface $createCategoryDto): bool
    {
        if($this->categoryRepository->findOneBy(['name'=>$createCategoryDto->getName()])){
            throw new FrontApiException('Category with this name exists');
        }
        $parent = null;
        if($createCategoryDto->getParentCategoryId()){
            $parent = $this->categoryRepository->find($createCategoryDto->getParentCategoryId());
            if(!$parent){
                throw new FrontApiException('The parent category does not exist');
            }
        }
        $category = new Category();
        $category->setName($createCategoryDto->getName());
        if($parent) $category->setParent($parent);
        $this->em->persist($category);
        $this->em->flush();
        return true;
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
        if($createEmployeeDto->getCategoryId()){
            return $this->employeeRepository->findBy(['category'=>$createEmployeeDto->getCategoryId()]);
        }
        return $this->employeeRepository->findAll();
    }
}



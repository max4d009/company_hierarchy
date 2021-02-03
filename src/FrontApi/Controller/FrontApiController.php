<?php

namespace App\FrontApi\Controller;

use App\FrontApi\Dto\Request\V1\CreateCategoryDto;
use App\FrontApi\Dto\Request\V1\CreateEmployeeDto;
use App\FrontApi\Dto\Request\V1\GetCategoriesRequestDto;
use App\FrontApi\Dto\Request\V1\GetEmployeesRequestDto;
use App\FrontApi\Dto\Response\V1\GetCategoriesResponseDto;
use App\FrontApi\Dto\Response\V1\GetEmployeesResponseDto;
use App\FrontApi\Dto\Response\V1\SuccessResponseDto;
use App\FrontApi\Dto\Response\V1\ErrorResponseDto;
use App\Service\FrontApiVersions\FrontApiContext;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;


/**
 * @OA\Parameter(
 *     name="version",
 *     example="v1",
 *     in="path",
 *     description="API version",
 *     @OA\Schema(type="string"),
 * )
 * @Route("/front-api/{version}")
 */
class FrontApiController extends AbstractFOSRestController
{
    private FrontApiContext $frontApiContext;

    public function __construct(FrontApiContext $frontApiContext)
    {
        $this->frontApiContext = $frontApiContext;
    }

    /**
     * Get a List of categories
     *
     * @OA\Response(
     *     response="200",
     *     @Model(type=GetCategoriesResponseDto::class, groups={"categories_req"}),
     *      description="Get a List of category"
     * )
     * @Rest\Get("/categories")
     * @Rest\View(statusCode=200, serializerGroups={"categories_req"})
     * @OA\Response(
     *     response="401",
     *     @Model(type=ErrorResponseDto::class),
     *      description="Get a List of category error"
     * )
     * @OA\Response(
     *     response="400",
     *     @Model(type=ErrorResponseDto::class),
     *      description="Get a List of category error"
     * )
     * @param GetCategoriesRequestDto $dto
     * @param string $version
     * @return GetCategoriesResponseDto
     * @throws \Exception
     */
    public function getCategories(GetCategoriesRequestDto $dto, string $version)
    {
        $categoryList = $this->frontApiContext->getApiService($version)->getCategories($dto);
        return GetCategoriesResponseDto::fetch($categoryList);
    }

    /**
     * Get a list of employees
     *
     * @Rest\Get("/employees")
     * @OA\Parameter(
     *     name="categoryId",
     *     example="1",
     *     in="query",
     *     description="Parent category id",
     *     @OA\Schema(type="integer"),
     * )
     * @OA\Response(
     *     response="200",
     *     @Model(type=GetEmployeesResponseDto::class, groups={"employees_req"}),
     *      description="Get a List of employees"
     * )
     * @OA\Response(
     *     response="401",
     *     @Model(type=ErrorResponseDto::class),
     *      description="Get a List of employees error"
     * )
     * @OA\Response(
     *     response="400",
     *     @Model(type=ErrorResponseDto::class),
     *      description="Get a List of employees error"
     * )
     * @Rest\View(statusCode=200, serializerGroups={"employees_req"})
     *
     * @param GetEmployeesRequestDto $dto
     * @param string $version
     * @return GetEmployeesResponseDto
     * @throws \Exception
     */
    public function getEmployees(GetEmployeesRequestDto $dto, string $version)
    {
        $employeeList = $this->frontApiContext->getApiService($version)->getEmployees($dto);
        return GetEmployeesResponseDto::fetch($employeeList);
    }

    /**
     * Add a new category
     *
     * @OA\RequestBody(
     *     @Model(type=CreateCategoryDto::class)
     * )
     * @OA\Response(
     *     response="200",
     *     @Model(type=SuccessResponseDto::class),
     *      description="Add a new category"
     * )
     * @OA\Response(
     *     response="401",
     *     @Model(type=ErrorResponseDto::class),
     *      description="Add a new category"
     * )
     * @OA\Response(
     *     response="400",
     *     @Model(type=ErrorResponseDto::class),
     *      description="Add a new category"
     * )
     * @Rest\Post("/category")
     * @Rest\View(statusCode=200)
     *
     * @param CreateCategoryDto $dto
     * @param string $version
     * @return SuccessResponseDto
     * @throws \Exception
     */
    public function addCategory(CreateCategoryDto $dto, string $version)
    {
        $result = $this->frontApiContext->getApiService($version)->createCategory($dto);
        return SuccessResponseDto::fetch($result);
    }


    /**
     * Add a new employee
     *
     * @OA\RequestBody(
     *     @Model(type=CreateEmployeeDto::class)
     * )
     * @OA\Response(
     *     response="200",
     *     @Model(type=SuccessResponseDto::class),
     *      description="Add a new employee"
     * )
     * @Rest\Post("/employee")
     * @Rest\View(statusCode=200)
     * @OA\Response(
     *     response="401",
     *     @Model(type=ErrorResponseDto::class),
     *      description="Add a new employee"
     * )
     * @OA\Response(
     *     response="400",
     *     @Model(type=ErrorResponseDto::class),
     *      description="Add a new employee"
     * )
     * @param CreateEmployeeDto $dto
     * @param string $version
     * @return SuccessResponseDto
     * @throws \Exception
     */
    public function addEmployee(CreateEmployeeDto $dto, string $version)
    {
        $result = $this->frontApiContext->getApiService($version)->createEmployee($dto);
        return SuccessResponseDto::fetch($result);
    }


}

<?php

namespace App\FrontApi\Controller;

use App\FrontApi\Dto\Request\V1\GetCategoriesRequestDto;
use App\FrontApi\Dto\Request\V1\GetEmployeesRequestDto;
use App\FrontApi\Dto\Response\V1\CategoryDto;
use App\FrontApi\Dto\Response\V1\EmployeeDto;
use App\FrontApi\Dto\Response\V1\GetCategoriesResponseDto;
use App\FrontApi\Dto\Response\V1\GetEmployeesResponseDto;
use App\Service\FrontApiVersions\FrontApiContext;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;


/**
 *
 *
 * @Route("/front-api")
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
     * @OA\Parameter(
     *     name="version",
     *     example="v1",
     *     in="path",
     *     description="API Version",
     *     @OA\Schema(type="string"),
     * )
     *
     * @OA\Response(
     *     response="200",
     *     @Model(type=GetCategoriesResponseDto::class),
     *      description="Get a List of category"
     * )
     * @Rest\Get("/{version}/categories")
     * @Rest\View(statusCode=200)
     *
     * @param GetCategoriesRequestDto $dto
     * @param string $version
     * @return array
     * @throws \Exception
     */
    public function getCategories(GetCategoriesRequestDto $dto, string $version)
    {
        $categoryList = $this->frontApiContext->getApiService($version)->getCategories($dto);
        return CategoryDto::list($categoryList);
    }

    /**
     * Get a list of employees
     *
     * @Rest\Get("/{version}/employees")
     *
     * @OA\Parameter(
     *     name="version",
     *     example="v1",
     *     in="path",
     *     description="API Version",
     *     @OA\Schema(type="string"),
     * )
     *
     * @OA\Response(
     *     response="200",
     *     @Model(type=GetEmployeesResponseDto::class, groups={"employees_req"}),
     *      description="Get a List of employees"
     * )
     *
     * @Rest\View(statusCode=200)
     *
     * @param GetEmployeesRequestDto $dto
     * @param string $version
     * @return array
     * @throws \Exception
     */
    public function getEmployees(GetEmployeesRequestDto $dto, string $version)
    {
        $categoryList = $this->frontApiContext->getApiService($version)->getEmployees($dto);
        return EmployeeDto::list($categoryList);
    }


}

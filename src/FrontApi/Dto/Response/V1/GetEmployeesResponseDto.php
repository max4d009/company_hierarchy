<?php

namespace App\FrontApi\Dto\Response\V1;


use App\FrontApi\Dto\Response\BaseResponseDto;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

/**
 * Class GetEmployeesResponseDto
 */
class GetEmployeesResponseDto extends BaseResponseDto
{
    /**
     * @OA\Property(
     *      type="array",
     *      @OA\Items(ref=@Model(type=EmployeeDto::class)),
     *      description="List of employees"
     * )
     * @Serializer\Groups({"employees_req"})
     */
    private array $employees;


    public static function fetch(array $employeeList)
    {
        $response = new self();
        foreach ($employeeList as $employee){
            $dto = new EmployeeDto();
            $dto->fillFromEntity($employee);
            $response->employees[] = $dto;
        }
        return $response;
    }
}

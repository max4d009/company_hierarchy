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
     *      description="bla bla bla"
     * )
     * @Serializer\Groups({"employees_req"})
     */
    private EmployeeDto $employees;
}

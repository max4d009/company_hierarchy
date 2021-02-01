<?php

namespace App\FrontApi\Dto\Response\V1;


use App\FrontApi\Dto\Response\BaseResponseDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class GetCategoriesResponseDto
 */
class GetCategoriesResponseDto extends BaseResponseDto
{
    /**
     * @OA\Property(
     *      type="array",
     *      @OA\Items(ref=@Model(type=CategoryDto::class)),
     *      description="bla bla bla"
     * )
     * @Serializer\Groups({"employees_req"})
     */
    private CategoryDto $categories;

}

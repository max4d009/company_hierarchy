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
     *      description="List of categories"
     * )
     * @Serializer\Groups({"employees_req"})
     */
    private array $categories;


    public static function fetch(array $categoryList)
    {
        $response = new self();
        foreach ($categoryList as $category){
            $dto = new CategoryDto();
            $dto->fillFromEntity($category);
            $response->categories[] = $dto;
        }
        return $response;
    }
}

<?php

namespace App\FrontApi\Dto\Response\V1;


use App\Entity\Category;
use App\FrontApi\Dto\Response\BaseResponseDto;
use OpenApi\Annotations as OA;

/**
 * Class GetCategoriesResponseDto
 */
class GetCategoriesResponseDto extends BaseResponseDto
{
    /**
     * @var int
     * @OA\Property(description="The unique identifier of the user.")
     */
    public int $id;

    /**
     * @OA\Property(type="string", maxLength=100)
     */
    public string $name;


    /**
     * @param array $categoryList
     * @return array
     */
    public static function list(array $categoryList)
    {
        $result = [];
        foreach ($categoryList as $category){
            $response = new self();
            $response->fillFromEntity($category);
            $result[] = $response;
        }
        return $result;
    }

    /**
     * @param Category $category
     */
    private function fillFromEntity(Category $category)
    {
        $this->id = $category->getId();
        $this->name = $category->getName();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

}

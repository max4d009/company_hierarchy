<?php

namespace App\FrontApi\Dto\Response\V1;

use App\Entity\Category;
use OpenApi\Annotations as OA;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Category
 */
class CategoryDto
{
    /**
     * @var int
     * @OA\Property(description="The unique identifier of the category.")
     * @Serializer\Groups({"employees_req"})
     */
    public int $id;

    /**
     * @var string
     * @OA\Property(description="The name of category.")
     * @Serializer\Groups({"employees_req"})
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
    public function fillFromEntity(Category $category)
    {
        $this->id = $category->getId();
        $this->name = $category->getName();
    }
}

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
     * @Serializer\Groups({"categories_req","employees_req"})
     */
    private int $id;

    /**
     * @var string
     * @OA\Property(description="The name of category.")
     * @Serializer\Groups({"categories_req","employees_req"})
     */
    private string $name;

    /**
     * @param Category $category
     */
    public function fillFromEntity(Category $category)
    {
        $this->id = $category->getId();
        $this->name = $category->getName();
    }
}

<?php

namespace App\FrontApi\Dto\Response\V1;



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


}

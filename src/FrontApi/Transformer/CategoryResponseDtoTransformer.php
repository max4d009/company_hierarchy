<?php


namespace App\FrontApi\Transformer;


use App\Entity\Category;
use App\FrontApi\Dto\Response\V1\GetCategoriesResponseDto;

class CategoryResponseDtoTransformer
{
    /**
     *
     * @return string
     */
    public function supportedClass(): string
    {
        return Category::class;
    }


    /**
     * @param Category $category
     *
     * @return GetCategoriesResponseDto
     */
    protected function prepare($category): GetCategoriesResponseDto
    {
        $categoryResponseModel = new GetCategoriesResponseDto();

        $categoryResponseModel->setId($category->getId());
        $categoryResponseModel->setName($category->getName());

        return $categoryResponseModel;
    }
}
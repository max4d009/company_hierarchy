<?php

namespace App\FrontApi\Controller;

use App\FrontApi\Dto\Request\V1\GetCategoriesRequestDto;
use App\FrontApi\Dto\Response\V1\GetCategoriesResponseDto;
use App\Service\FrontApiVersions\FrontApiContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/front-api")
 */
class Controller extends AbstractController
{
    private FrontApiContext $frontApiContext;

    public function __construct(FrontApiContext $frontApiContext)
    {
        $this->frontApiContext = $frontApiContext;
    }

    /**
     *
     * @Route("/{ver}/categories", methods={"GET"})
     * @throws \Exception
     */
    public function getCategories(GetCategoriesRequestDto $dto, string $ver)
    {
        $categoryList = $this->frontApiContext->getApiService($ver)->getCategories($dto);
        return GetCategoriesResponseDto::list($categoryList);
    }

}

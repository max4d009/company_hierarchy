<?php

namespace App\FrontApi\ParamConverter;

use App\FrontApi\Dto\Request\BaseRequestDto;
use App\FrontApi\Exception\FrontApiException;
use Doctrine\Common\Annotations\AnnotationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RequestToDtoConverter
 */
class RequestToDtoConverter implements ParamConverterInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * AddStatConverter constructor.
     * @param ContainerInterface $container
     * @param ValidatorInterface $validator
     */
    function __construct(ContainerInterface $container, ValidatorInterface $validator)
    {
        $this->container = $container;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool|void
     * @throws AnnotationException
     * @throws \ReflectionException
     * @throws FrontApiException
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        /* @var BaseRequestDto $dto */
        $class = $configuration->getClass();
        $dto = new $class();
        $dto->resolveByRequest($request);
        $request->attributes->set($configuration->getName(), $dto);
    }

    /**
     * @param ParamConverter $configuration
     * @return bool
     */
    public function supports(ParamConverter $configuration)
    {
        return is_a($configuration->getClass(), BaseRequestDto::class, true);
    }
}



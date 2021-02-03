<?php

namespace App\FrontApi\Listener;

use App\FrontApi\Dto\Response\V1\ErrorResponseDto;
use App\FrontApi\Exception\FrontApiException;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class ExceptionListener
 * @package App\FrontApi\Listener
 */
class ExceptionListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    private SerializerInterface $serializer;

    /**
     * ExceptionListener constructor.
     * @param LoggerInterface $frontApiLogger
     * @param SerializerInterface $serializer
     */
    function __construct(LoggerInterface $frontApiLogger, SerializerInterface $serializer)
    {
        $this->logger = $frontApiLogger;
        $this->serializer = $serializer;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
//dump($exception);die();
        $this->logger->error("Error: {$exception->getCode()}; Message: {$exception->getMessage()}");


        if($exception instanceof FrontApiException) {
            $dto = ErrorResponseDto::fetch($exception->getMessage(), $exception->getCode());
        } else {
            $dto = ErrorResponseDto::fetch('Unknown error', $exception->getCode());
        }

        $content = $this->serializer->serialize($dto, 'json');
        $response = new Response($content);
//        $response->setStatusCode($exception->getCode());
        $event->setResponse($response);
     }
}

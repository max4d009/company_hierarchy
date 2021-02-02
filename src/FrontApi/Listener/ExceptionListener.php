<?php

namespace App\FrontApi\Listener;

use App\FrontApi\Exception\FrontApiException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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


    /**
     * ExceptionListener constructor.
     * @param LoggerInterface $frontApiLogger
     */
    function __construct(LoggerInterface $frontApiLogger)
    {
        $this->logger = $frontApiLogger;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if($exception instanceof FrontApiException) {
            $response = new JsonResponse(['error' => $exception->getCode(), 'message' => $exception->getMessage()]);
        } else {
            $response = new JsonResponse(['error' => $exception->getCode(), 'message' => 'Unknown Error']);
        }

        $this->logger->error("Error: {$exception->getCode()}; Message: {$exception->getMessage()}");

        $response->setStatusCode(401);
        $event->setResponse($response);

        $event->setResponse($response);
     }
}

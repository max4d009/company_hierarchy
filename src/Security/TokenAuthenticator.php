<?php

namespace App\Security;

use App\FrontApi\Exception\FrontApiException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthenticator extends AbstractGuardAuthenticator
{

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new FrontApiException('Authentication Required', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        if($request->getMethod() != Request::METHOD_GET){
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     * @throws FrontApiException
     */
    public function getCredentials(Request $request)
    {
        if(!$request->headers->has('X-AUTH-TOKEN')){
            throw new FrontApiException('X-AUTH-TOKEN needed', Response::HTTP_UNAUTHORIZED);
        }
        return $request->headers->get('X-AUTH-TOKEN');
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (null === $credentials) {
            return null;
        }
        return $userProvider->loadUserByUsername($credentials);
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        // Todo: It's only an example. Need JWT.
        if($user->getApiToken() == $credentials){
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     * @throws FrontApiException
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new FrontApiException('X-AUTH-TOKEN bad', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
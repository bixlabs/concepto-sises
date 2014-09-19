<?php
/**
 * Copyright © 2014 Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 *
 * This file is part of concepto-sises.
 *
 * concepto-sises
 * can not be copied and/or distributed without the express
 * permission of Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 */

namespace Concepto\Sises\ApplicationBundle\Seguridad;

use Concepto\Sises\ApplicationBundle\Entity\Seguridad\Usuario;
use Concepto\Sises\ApplicationBundle\Seguridad\Provider\UsuarioProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * Class ApiAuthenticator
 * @Service(id="concepto_sises_api.authenticator")
 */
class ApiAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    const API_HEADER = 'X-Api-Key';
    const API_QUERY = 'apiKey';

    /**
     * @param TokenInterface                         $token
     * @param UsuarioProvider|UserProviderInterface  $userProvider
     * @param                                        $providerKey
     *
     * @return PreAuthenticatedToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $apiKey = $token->getCredentials();
        /** @var Usuario $user */
        $user = $userProvider->loadUserByToken($apiKey);

        if (!$user) {
            throw new AuthenticationException(sprintf(
                "API Key '%s' does not exist!", $apiKey
            ));
        }

        return new PreAuthenticatedToken($user, $apiKey, $providerKey, $user->getRoles());
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $providerKey)
    {
        $apiKey = $request->headers->get(self::API_HEADER);

        if (!$apiKey) {
            $apiKey = $request->query->get(self::API_QUERY);
            $request->query->remove(self::API_QUERY);
        }

        if (!$apiKey) {
            throw new BadCredentialsException("No Api key found!");
        }

        return new PreAuthenticatedToken('anon.', $apiKey, $providerKey);
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new AccessDeniedHttpException($exception->getMessage());
    }
}
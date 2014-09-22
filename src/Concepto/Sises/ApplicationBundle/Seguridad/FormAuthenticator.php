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


use Concepto\Sises\ApplicationBundle\Seguridad\Provider\UsuarioProvider;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * Class FormAuthenticator
 * @package Concepto\Sises\ApplicationBundle\Seguridad
 * @Service(id="concepto_sises_form.authenticator")
 */
class FormAuthenticator implements
    SimpleFormAuthenticatorInterface,
    AuthenticationFailureHandlerInterface,
    AuthenticationSuccessHandlerInterface
{
    /**
     * @var EncoderFactory
     */
    protected $factory;

    /**
     * @param $factory
     *
     * @InjectParams({
     *  "factory" = @Inject("security.encoder_factory")
     * })
     */
    function __construct($factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param TokenInterface                        $token
     * @param UserProviderInterface|UsuarioProvider $userProvider
     * @param                                       $providerKey
     *
     * @return UsernamePasswordToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $user = $userProvider->loadUserByUsername($token->getUsername());
        $tokenKey = $userProvider->validate($user, $token->getCredentials());

        return new UsernamePasswordToken(
            $user, $tokenKey, $providerKey, $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $response = new Response("", Response::HTTP_NO_CONTENT, array(
            ApiAuthenticator::API_HEADER => $token->getUser()->getToken())
        );

        return $response;
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
        return new Response("", Response::HTTP_UNAUTHORIZED);
    }
}
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


use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

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

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            $user = $userProvider->loadUserByUsername($token->getUsername());

            $valid = $this->factory->getEncoder($user)
                ->isPasswordValid($user->getPassword(), $token->getCredentials(), $user->getSalt());

            if ($valid) {
                return new UsernamePasswordToken(
                    $user, $token->getCredentials(), $providerKey, $user->getRoles()
                );
            }

        } catch (UsernameNotFoundException $e) {
        }

        throw new AuthenticationException("Username or password invalid!");
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
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new AccessDeniedHttpException($exception->getMessage());
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
        $response = new Response();
        $request->headers->add(array(ApiAuthenticator::API_HEADER => $token->getCredentials()));

        return $response;
    }
}
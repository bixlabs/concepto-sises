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

namespace Concepto\Sises\ApplicationBundle\Seguridad\Provider;


use Concepto\Sises\ApplicationBundle\Entity\Seguridad\Usuario;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\ClassUtils;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserProvider
 * @package Concepto\Sises\ApplicationBundle\Seguridad\Provider
 * @Service(id="concepto_sises_usuario.provider")
 */
class UsuarioProvider implements UserProviderInterface
{
    /**
     * @var ObjectManager
     */
    protected $em;

    /**
     * @var EncoderFactory
     */
    protected $factory;

    /**
     * @InjectParams({
     *  "em" = @Inject("doctrine.orm.default_entity_manager"),
     *  "factory" = @Inject("security.encoder_factory")
     * })
     */
    function __construct($em, $factory)
    {
        $this->em = $em;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $user = $this->em
            ->getRepository('SisesApplicationBundle:Seguridad\Usuario')
            ->findOneBy(array('nombre' => $username));

        if (!$user) {
            throw new UsernameNotFoundException(sprintf(
                'Username %s does not exist!', $username
            ));
        }

        return $user;
    }

    public function loadUserByToken($token)
    {
        return $this->em
            ->getRepository('SisesApplicationBundle:Seguridad\Usuario')
            ->findOneBy(array('token' => $token));
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Usuario) {
            throw new UnsupportedUserException(sprintf(
                'Instances of "%s" are not supported',
                ClassUtils::getClass($user)
            ));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param UserInterface $usuario
     * @param               $password
     * @return string
     */
    public function validate(UserInterface $usuario, $password)
    {
        $valid = $this->factory->getEncoder($usuario)
            ->isPasswordValid($usuario->getPassword(), $password, $usuario->getSalt());

        if ($valid) {
            return $this->generateToken($usuario);
        }

        throw new AuthenticationException("Username or password invalid!");
    }

    public function generateToken(UserInterface $user)
    {
        if (!$user instanceof Usuario) {
            throw new UnsupportedUserException(sprintf(
                'Instances of "%s" are not supported',
                ClassUtils::getClass($user)
            ));
        }
        $token = base64_encode(hash('sha256', uniqid('_token')));
        $token = substr($token, 0, -2);

        $user->setToken($token);
        $this->em->persist($user);
        $this->em->flush();

        return $token;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return Usuario::class === $class;
    }}
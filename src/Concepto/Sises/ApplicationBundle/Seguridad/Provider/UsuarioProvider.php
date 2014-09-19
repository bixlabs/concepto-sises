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
     * @InjectParams({
     *  "em" = @Inject("doctrine.orm.default_entity_manager")
     * })
     */
    function __construct($em)
    {
        $this->em = $em;
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
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return Usuario::class === $class;
    }}
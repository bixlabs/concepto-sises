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

namespace Concepto\Sises\ApplicationBundle\Handler\Seguridad;

use Concepto\Sises\ApplicationBundle\Entity\Seguridad\Usuario;
use FOS\UserBundle\Model\UserManagerInterface;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;

/**
 * Class UserManipulator
 * @package Concepto\Sises\ApplicationBundle\Handler\Seguridad
 * @Service(id="concepto.sises.user_manipulator")
 */
class UserManipulator
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @param UserManagerInterface $userManager
     * @InjectParams({"userManager" = @Inject("fos_user.user_manager")})
     */
    function __construct($userManager)
    {
        $this->userManager = $userManager;
    }


    public function create($username, $password, $email, $active, $superadmin, $related, $tipo)
    {
        /** @var Usuario $user */
        $user = $this->userManager->createUser();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled((Boolean) $active);
        $user->setSuperAdmin((Boolean) $superadmin);
        $user->setRelated($related);
        $user->setTipo($tipo);
        $this->userManager->updateUser($user);

        return $user;
    }

    public function deteleAll()
    {
        $users = $this->userManager->findUsers();

        foreach($users as $user) {
            $this->userManager->deleteUser($user);
        }
    }

    public function addRole($username, $role)
    {
        $user = $this->userManager->findUserByUsername($username);

        if (!$user) {
            throw new \InvalidArgumentException(sprintf('User identified by "%s" username does not exist.', $username));
        }
        if ($user->hasRole($role)) {
            return false;
        }
        $user->addRole($role);
        $this->userManager->updateUser($user);

        return true;
    }
} 
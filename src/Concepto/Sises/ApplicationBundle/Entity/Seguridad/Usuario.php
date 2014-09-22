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

namespace Concepto\Sises\ApplicationBundle\Entity\Seguridad;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PostLoad;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Usuario
 * @package Concepto\Sises\ApplicationBundle\Entity\Seguridad
 * @Entity()
 * @Table(name="seguridad_usuario")
 * @HasLifecycleCallbacks()
 */
class Usuario implements UserInterface
{
    /**
     * @var string
     * @Id()
     * @Column(name="id", length=36)
     * @GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var string
     * @Column(name="nombre", length=250, unique=true)
     */
    protected $nombre;

    /**
     * @var string
     * @Column(name="password", length=64, nullable=false)
     */
    protected $password;

    /**
     * @var string
     * @Column(name="salt", length=64, nullable=false)
     */
    protected $salt;

    /**
     * @var string
     * @Column(name="roles", type="text")
     */
    protected $rolesString;

    /**
     * @var string
     * @Column(name="token", length=86, nullable=true)
     */
    protected $token;

    protected $rolesArray;

    function __construct()
    {
        $this->rolesArray = array('ROLE_API');
        $this->salt = hash('sha256', uniqid('s_'));
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->rolesArray;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        $this->getNombre();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getRolesString()
    {
        return $this->rolesString;
    }

    /**
     * @param string $rolesString
     */
    public function setRolesString($rolesString)
    {
        $this->rolesString = $rolesString;
    }

    /**
     * @PrePersist()
     * @PreUpdate()
     */
    public function storeRoles()
    {
        $this->rolesString = json_encode($this->rolesArray);
    }

    /**
     * @PostLoad()
     */
    public function loadRoles()
    {
        $this->rolesArray = json_decode($this->rolesString, true);
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }
}
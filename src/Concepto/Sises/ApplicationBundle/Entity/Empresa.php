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

namespace Concepto\Sises\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class Empresa
 * @package Concepto\Sises\ApplicationBundle\Entity
 * @Entity()
 * @Table(name="empresa")
 * @UniqueEntity(fields={"nit"}, message="No puede existir dos empresas con un mismo nit")
 */
class Empresa implements OrmPersistible {
    /**
     * @var string
     * @Id()
     * @Column(name="id", length=36)
     * @GeneratedValue(strategy="UUID")
     * @Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var string
     * @Column(name="nombre", length=250, nullable=false)
     * @NotBlank()
     * @Groups({"list", "details"})
     */
    protected $nombre;

    /**
     * @var string
     * @Column(name="nit", length=15, unique=true, nullable=false)
     * @NotBlank()
     * @Groups({"list", "details"})
     */
    protected $nit;

    /**
     * @var string
     * @Column(name="logo", length=255, nullable=true)
     * @Groups({"list", "details"})
     */
    protected $logo;

    /**
     * @var string
     * @Column(name="telefono", type="string", nullable=true)
     * @Groups({"list", "details"})
     */
    protected $telefono;

    /**
     * @var string
     * @Column(name="direccion", type="string", nullable=true, length=255)
     * @Groups({"list", "details"})
     */
    protected $direccion;

    /**
     * @var string
     * @Column(name="email", type="string", nullable=true, length=255)
     * @Email()
     * @Groups({"list", "details"})
     */
    protected $email;

    /**
     * @var PersonaCargo
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\PersonaCargo", cascade={"persist"})
     * @JoinColumn(nullable=true)
     * @Groups({"list"})
     */
    protected $encargado;

    /**
     * @VirtualProperty()
     * @SerializedName("encargado")
     * @Groups({"details"})
     */
    public function getEncargadoId()
    {
        if ($this->encargado) {
            return $this->encargado->getId();
        }

        return null;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * @param string $nit
     */
    public function setNit($nit)
    {
        $this->nit = $nit;
    }

    /**
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param string $direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return \Concepto\Sises\ApplicationBundle\Entity\PersonaCargo
     */
    public function getEncargado()
    {
        return $this->encargado;
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\PersonaCargo $encargado
     */
    public function setEncargado($encargado)
    {
        $this->encargado = $encargado;
    }
}
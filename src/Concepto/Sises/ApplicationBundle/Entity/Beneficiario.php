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
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class Beneficiario
 * @package Concepto\Sises\ApplicationBundle\Entity
 * @Entity()
 * @Table(
 *      name="beneficiario",
 *      uniqueConstraints={
 *          @UniqueConstraint(name="beneficiario", columns={"lugar_id", "servicio_id", "persona_id"})
 *      }
 * )
 * @UniqueEntity(
 *      message="No puede brindar un servcio dos veces a una misma persona",
 *      fields={"lugar","servicio","persona"}
 * )
 */
class Beneficiario {
    /**
     * @var string
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", length=36)
     * @Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var LugarEntrega
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\LugarEntrega")
     * @NotNull()
     * @Groups({"list"})
     */
    protected $lugar;

    /**
     * @var ServicioContratado
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\ServicioContratado")
     * @NotNull()
     * @Groups({"list"})
     */
    protected $servicio;

    /**
     * @var Persona
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Persona")
     * @NotNull()
     * @Groups({"list"})
     */
    protected $persona;

    /**
     * @VirtualProperty()
     * @SerializedName("persona")
     * @Groups({"details"})
     */
    public function getPersonaId()
    {
        return $this->persona->getId();
    }

    /**
     * @VirtualProperty()
     * @SerializedName("servicio")
     * @Groups({"details"})
     */
    public function getServicioId()
    {
        return $this->servicio->getId();
    }

    /**
     * @VirtualProperty()
     * @SerializedName("lugar")
     * @Groups({"details"})
     */
    public function getLugarId()
    {
        return $this->lugar->getId();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Concepto\Sises\ApplicationBundle\Entity\LugarEntrega
     */
    public function getLugar()
    {
        return $this->lugar;
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\LugarEntrega $lugar
     */
    public function setLugar($lugar)
    {
        $this->lugar = $lugar;
    }

    /**
     * @return \Concepto\Sises\ApplicationBundle\Entity\ServicioContratado
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\ServicioContratado $servicio
     */
    public function setServicio($servicio)
    {
        $this->servicio = $servicio;
    }

    /**
     * @return \Concepto\Sises\ApplicationBundle\Entity\Persona
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Persona $persona
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;
    }
}
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


use Concepto\Sises\ApplicationBundle\Entity\Personal\Coordinador;


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
 * Class CoordinadorAsignacion
 * @package Concepto\Sises\ApplicationBundle\Entity
 * @Entity()
 * @Table(name="asignacion_coordinador",
 *      uniqueConstraints={
 *          @UniqueConstraint(name="coordinador", columns={"lugar_id", "servicio_id", "coordinador_id"})
 *      }
 * )
 * @UniqueEntity(
 *      message="No puede asignar un coordinador dos veces al mismo servicio y lugar",
 *      fields={"lugar","servicio","coordinador"}
 * )
 */
class CoordinadorAsignacion {
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
     * @var Coordinador
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Personal\Coordinador", inversedBy="asignacion")
     * @NotNull()
     * @Groups({"list"})
     */
    protected $coordinador;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Coordinador
     */
    public function getCoordinador()
    {
        return $this->coordinador;
    }

    /**
     * @param Coordinador $coordinador
     */
    public function setCoordinador($coordinador)
    {
        $this->coordinador = $coordinador;
    }

    /**
     * @return LugarEntrega
     */
    public function getLugar()
    {
        return $this->lugar;
    }

    /**
     * @param LugarEntrega $lugar
     */
    public function setLugar($lugar)
    {
        $this->lugar = $lugar;
    }

    /**
     * @return ServicioContratado
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * @param ServicioContratado $servicio
     */
    public function setServicio($servicio)
    {
        $this->servicio = $servicio;
    }

    /**
     * @VirtualProperty()
     * @SerializedName("coordinador")
     * @Groups({"details"})
     */
    public function getRelatedId()
    {
        return $this->coordinador->getId();
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
}
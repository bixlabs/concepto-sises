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

namespace Concepto\Sises\ApplicationBundle\Entity\Entrega;


use Concepto\Sises\ApplicationBundle\Entity\ServicioContratado;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class EntregaBeneficio
 * @package Concepto\Sises\ApplicationBundle\Entity\Entrega
 * @Entity(repositoryClass="Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaBeneficioRepository")
 * @Table(name="entrega_beneficio")
 */
class EntregaBeneficio
{
    /**
     * @var string
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", length=36)
     * @Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var EntregaAsignacion
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaAsignacion")
     * @Exclude()
     */
    protected $entrega;

    /**
     * @var ServicioContratado
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\ServicioContratado")
     * @Exclude()
     */
    protected $servicio;

    /**
     * @var \DateTime
     * @Column(name="fecha_entrega", type="datetime")
     * @NotNull()
     * @Groups({"details", "list"})
     * @SerializedName("fechaEntrega")
     */
    protected $fechaEntrega;

    /**
     * @VirtualProperty()
     * @SerializedName("entrega")
     * @Groups({"details"})
     *
     * @return string
     */
    public function getEntregaId()
    {
        return $this->getEntrega()->getId();
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fechaEntrega
     *
     * @param \DateTime $fechaEntrega
     *
     * @return EntregaBeneficio
     */
    public function setFechaEntrega($fechaEntrega)
    {
        $this->fechaEntrega = $fechaEntrega;

        return $this;
    }

    /**
     * Get fechaEntrega
     *
     * @return \DateTime
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * Set entrega
     *
     * @param EntregaAsignacion $entrega
     *
     * @return EntregaBeneficio
     */
    public function setEntrega(EntregaAsignacion $entrega = null)
    {
        $this->entrega = $entrega;

        return $this;
    }

    /**
     * Get entrega
     *
     * @return EntregaAsignacion
     */
    public function getEntrega()
    {
        return $this->entrega;
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
     * @return string
     * @VirtualProperty()
     * @SerializedName("servicio")
     */
    public function getServicioId()
    {
        return $this->getServicio()->getId();
    }
}

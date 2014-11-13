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

use Concepto\Sises\ApplicationBundle\Entity\CoordinadorAsignacion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Class EntregaAsignacion
 * @package Concepto\Sises\ApplicationBundle\Entity\Entrega
 * @Entity()
 * @Table("entrega_asignacion")
 */
class EntregaAsignacion
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
     * @var CoordinadorAsignacion
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\CoordinadorAsignacion")
     */
    protected $asignacion;

    /**
     * @var Entrega
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega")
     * @Groups({"details"})
     * @MaxDepth(depth=1)
     */
    protected $entrega;

    /**
     * @var Collection
     * @OneToMany(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaBeneficio", mappedBy="entrega")
     * @Groups({"details"})
     */
    protected $realizadas;

    /**
     * @var bool
     * @Column(name="is_manual", type="boolean", nullable=false)
     * @Groups({"list", "details"})
     */
    protected $isCierreManual;

    function __construct()
    {
        $this->realizadas = new ArrayCollection();
        $this->isCierreManual = false;
    }

    /**
     * @VirtualProperty()
     * @SerializedName("asignacion")
     * @Groups({"details"})
     */
    public function getAsignacionDetalles()
    {
        $asignacion = $this->getAsignacion();

        return array(
            'id' => $asignacion->getId(),
            'lugar' => $asignacion->getLugar()->getNombreDetallado(),
            'servicio' => $asignacion->getServicio()->getNombre()
        );
    }

    /**
     * @VirtualProperty()
     * @SerializedName("nombre_detallado")
     * @Groups({"list"})
     */
    public function getNombreDetallado()
    {
        $asignacion = $this->getAsignacion();
        $entrega = $this->getEntrega();

        $fecha = "{$entrega->getFechaInicio()->format('d M Y')} - {$entrega->getFechaCierre()->format('d M Y')}";

        return "{$asignacion->getServicio()->getNombre()} - {$asignacion->getLugar()->getNombreDetallado()}, {$fecha}";
    }

    /**
     * @return array
     * @SerializedName("fechas")
     * @VirtualProperty()
     * @Groups({"list"})
     */
    public function getFechas()
    {
        $entrega = $this->getEntrega();

        return array(
            'inicio' => $entrega->getFechaInicio(),
            'cierre' => $entrega->getFechaCierre(),
        );
    }

    /**
     * Set asignacion
     *
     * @param CoordinadorAsignacion $asignacion
     *
     * @return EntregaAsignacion
     */
    public function setAsignacion(CoordinadorAsignacion $asignacion = null)
    {
        $this->asignacion = $asignacion;

        return $this;
    }

    /**
     * Get asignacion
     *
     * @return CoordinadorAsignacion
     */
    public function getAsignacion()
    {
        return $this->asignacion;
    }

    /**
     * Set isCierreManual
     *
     * @param boolean $isCierreManual
     *
     * @return EntregaAsignacion
     */
    public function setIsCierreManual($isCierreManual)
    {
        $this->isCierreManual = $isCierreManual;

        return $this;
    }

    /**
     * Get isCierreManual
     *
     * @return boolean
     */
    public function getIsCierreManual()
    {
        return $this->isCierreManual;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Entrega
     */
    public function getEntrega()
    {
        return $this->entrega;
    }

    /**
     * @param Entrega $entrega
     */
    public function setEntrega($entrega)
    {
        $this->entrega = $entrega;
    }

    /**
     * @return Collection
     */
    public function getRealizadas()
    {
        return $this->realizadas;
    }

    /**
     * @param Collection $realizadas
     */
    public function setRealizadas($realizadas)
    {
        $this->realizadas = $realizadas;
    }
}

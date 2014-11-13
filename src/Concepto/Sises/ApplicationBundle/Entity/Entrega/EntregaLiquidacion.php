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


use Concepto\Sises\ApplicationBundle\Entity\Contrato;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class EntregaLiquidacion
 * @package Concepto\Sises\ApplicationBundle\Entity\Entrega
 * @Entity()
 * @Table(name="entrega_liquidacion")
 */
class EntregaLiquidacion
{
    const OPEN = 'pendiente';
    const CLOSE = 'finalizada';

    /**
     * @var string
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", length=36)
     * @Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var \DateTime
     * @Column(name="fecha_inicio", type="datetime")
     * @NotNull()
     * @Groups({"list", "details"})
     * @SerializedName("fechaInicio")
     */
    protected $fechaInicio;

    /**
     * @var \DateTime
     * @Column(name="fecha_cierre", type="datetime")
     * @NotNull()
     * @Groups({"list", "details"})
     * @SerializedName("fechaCierre")
     */
    protected $fechaCierre;

    /**
     * @var int
     * @Column(name="dias_gracia", type="integer")
     * @NotBlank()
     * @Groups({"list", "details"})
     * @SerializedName("diasGracia")
     */
    protected $diasGracia;

    /**
     * @var string
     * @Column(name="estado", length=100, nullable=false)
     * @Groups({"list", "details"})
     */
    protected $estado;

    /**
     * @var Contrato
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Contrato", fetch="LAZY")
     * @NotNull()
     * @JoinColumn(nullable=false)
     * @Groups({"list"})
     * @MaxDepth(depth=1)
     */
    protected $contrato;

    /**
     * @var Collection
     * @OneToMany(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaOperacion", mappedBy="liquidacion")
     */
    protected $detalles;

    function __construct()
    {
        $this->estado = self::OPEN;
        $this->detalles = new ArrayCollection();
    }

    /**
     * @return string
     * @VirtualProperty()
     * @SerializedName("contrato")
     * @Groups({"details"})
     */
    public function getContratoId()
    {
        return $this->contrato->getId();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Contrato
     */
    public function getContrato()
    {
        return $this->contrato;
    }

    /**
     * @param Contrato $contrato
     */
    public function setContrato($contrato)
    {
        $this->contrato = $contrato;
    }

    /**
     * @return int
     */
    public function getDiasGracia()
    {
        return $this->diasGracia;
    }

    /**
     * @param int $diasGracia
     */
    public function setDiasGracia($diasGracia)
    {
        $this->diasGracia = $diasGracia;
    }

    /**
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param string $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return \DateTime
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * @param \DateTime $fechaCierre
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;
    }

    /**
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * @param \DateTime $fechaInicio
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * @return Collection
     */
    public function getDetalles()
    {
        return $this->detalles;
    }

    /**
     * @param Collection $detalles
     */
    public function setDetalles($detalles)
    {
        $this->detalles = $detalles;
    }

    /**
     * @SerializedName("nombre_detallado")
     * @VirtualProperty()
     * @Groups({"list"})
     */
    public function getNombreDetallado()
    {
        return "{$this->getFechaInicio()->format('d M Y')} - {$this->getFechaCierre()->format('d M Y')}";
    }
} 
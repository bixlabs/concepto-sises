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
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class Entrega
 * @package Concepto\Sises\ApplicationBundle\Entity\Entrega
 * @Entity()
 * @Table(name="entrega_entrega")
 */
class Entrega {

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
     * @Groups({"details"})
     * @SerializedName("fechaInicio")
     */
    protected $fechaInicio;

    /**
     * @var \DateTime
     * @Column(name="fecha_cierre", type="datetime")
     * @NotNull()
     * @Groups({"details"})
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
     * @NotBlank()
     * @Groups({"list", "details"})
     */
    protected $estado;

    /**
     * @var Contrato
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Contrato", fetch="LAZY", inversedBy="servicios")
     * @NotNull()
     * @JoinColumn(nullable=false)
     * @Groups({"list"})
     * @MaxDepth(depth=1)
     */
    protected $contrato;


    function __construct()
    {
        $this->estado = self::OPEN;
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return Entrega
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaCierre
     *
     * @param \DateTime $fechaCierre
     * @return Entrega
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;

        return $this;
    }

    /**
     * Get fechaCierre
     *
     * @return \DateTime 
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Set diasGracia
     *
     * @param integer $diasGracia
     * @return Entrega
     */
    public function setDiasGracia($diasGracia)
    {
        $this->diasGracia = $diasGracia;

        return $this;
    }

    /**
     * Get diasGracia
     *
     * @return integer 
     */
    public function getDiasGracia()
    {
        return $this->diasGracia;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Entrega
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
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
     * @VirtualProperty()
     * @SerializedName("contrato")
     * @Groups({"details"})
     * @return string
     */
    public function getContratoId()
    {
        return $this->getContrato()->getId();
    }
}

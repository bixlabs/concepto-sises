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


use Concepto\Sises\ApplicationBundle\Entity\ServicioOperativo;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EntregaLiquidacionDetalle
 * @package Concepto\Sises\ApplicationBundle\Entity\Entrega
 * @ORM\Entity(repositoryClass="Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaLiquidacionDetalleRepository")
 * @ORM\Table(name="entrega_liquidacion_detalle")
 */
class EntregaLiquidacionDetalle
{

    /**
     * @var string
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(name="id", length=36)
     * @Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var ServicioOperativo
     * @ORM\ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\ServicioOperativo", inversedBy="liquidaciones")
     * @Assert\NotNull()
     */
    protected $servicio;

    /**
     * @var EntregaLiquidacion
     * @ORM\ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaLiquidacion", inversedBy="detalles")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    protected $liquidacion;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     * @Groups({"details", "list"})
     */
    protected $cantidad = 0;

    /**
     * @return ServicioOperativo
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * @return string
     * @VirtualProperty()
     * @SerializedName("servicio")
     * @Groups({"list"})
     */
    public function getServicioId()
    {
        return $this->getServicio()->getId();
    }

    /**
     * @param ServicioOperativo $servicio
     */
    public function setServicio($servicio)
    {
        $this->servicio = $servicio;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * @param int $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    /**
     * @return EntregaLiquidacion
     */
    public function getLiquidacion()
    {
        return $this->liquidacion;
    }

    /**
     * @param EntregaLiquidacion $liquidacion
     */
    public function setLiquidacion($liquidacion)
    {
        $this->liquidacion = $liquidacion;
    }
} 
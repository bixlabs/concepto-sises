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
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EntregaDetalle
 * @package Concepto\Sises\ApplicationBundle\Entity\Entrega
 * @ORM\Entity()
 * @ORM\Table(name="entrega_detalle")
 */
class EntregaDetalle
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(name="id", length=36)
     */
    protected $id;

    /**
     * @var Entrega
     * @ORM\ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega")
     * @Assert\NotNull()
     */
    protected $entrega;

    /**
     * @var ServicioContratado
     * @ORM\ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\ServicioContratado")
     * @Assert\NotNull()
     */
    protected $servicio;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $cantidad = 0;

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
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
} 
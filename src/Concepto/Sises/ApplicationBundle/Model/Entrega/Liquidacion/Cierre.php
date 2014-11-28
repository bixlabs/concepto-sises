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

namespace Concepto\Sises\ApplicationBundle\Model\Entrega\Liquidacion;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Cierre {

    protected $liquidacion;

    /**
     * @var Collection
     */
    protected $servicios;

    protected $observacion;

    function __construct()
    {
        $this->servicios = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getLiquidacion()
    {
        return $this->liquidacion;
    }

    /**
     * @param mixed $liquidacion
     */
    public function setLiquidacion($liquidacion)
    {
        $this->liquidacion = $liquidacion;
    }

    /**
     * @return mixed
     */
    public function getServicios()
    {
        return $this->servicios;
    }

    /**
     * @param mixed $servicios
     */
    public function setServicios($servicios)
    {
        $this->servicios = $servicios;
    }

    public function addServicio($servicio)
    {
        if (!$this->servicios->contains($servicio)) {
            $this->servicios->add($servicio);
        }
    }

    public function removeServicio($servicio)
    {
        if ($this->servicios->contains($servicio)) {
            $this->servicios->removeElement($servicio);
        }
    }

    /**
     * @return mixed
     */
    public function getObservacion()
    {
        return $this->observacion;
    }

    /**
     * @param mixed $observacion
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;
    }
} 
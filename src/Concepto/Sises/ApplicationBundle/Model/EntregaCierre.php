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

namespace Concepto\Sises\ApplicationBundle\Model;


use Doctrine\Common\Collections\ArrayCollection;

class EntregaCierre {

    /**
     * @var string
     */
    protected $id;

    /**
     * @var ArrayCollection
     */
    protected $servicios;

    function __construct()
    {
        $this->servicios = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getServicios()
    {
        return $this->servicios;
    }

    /**
     * @param ArrayCollection $servicios
     */
    public function setServicios($servicios)
    {
        $this->servicios = $servicios;
    }

    public function addServicio(EntregaCierreServicio $servicio)
    {
        if (!$this->servicios->contains($servicio)) {
            $this->servicios->add($servicio);
        }
    }

    public function removeServicio(EntregaCierreServicio $servicio)
    {
        if ($this->servicios->contains($servicio)) {
            $this->servicios->removeElement($servicio);
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
} 
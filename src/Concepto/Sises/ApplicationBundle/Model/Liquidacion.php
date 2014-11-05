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


use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaOperacion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Liquidacion {
    /**
     * @var Collection
     */
    protected $liquidaciones;

    function __construct()
    {
        $this->liquidaciones = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getLiquidaciones()
    {
        return $this->liquidaciones;
    }

    /**
     * @param Collection $liquidaciones
     */
    public function setLiquidaciones($liquidaciones)
    {
        $this->liquidaciones = $liquidaciones;
    }

    public function addLiquidacion(EntregaOperacion $item)
    {
        if (!$this->liquidaciones->contains($item)) {
            $this->liquidaciones->add($item);
        }
    }

    public function removeLiquidacion(EntregaOperacion $item)
    {
        if ($this->liquidaciones->contains($item)) {
            $this->liquidaciones->removeElement($item);
        }
    }
} 
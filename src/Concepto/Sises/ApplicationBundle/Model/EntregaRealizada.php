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


use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaBeneficioDetalle;
use Doctrine\Common\Collections\ArrayCollection;

class EntregaRealizada {
    /**
     * @var ArrayCollection
     */
    protected $entregas;

    function __construct()
    {
        $this->entregas = new ArrayCollection();
    }

    public function addEntrega(EntregaBeneficioDetalle $entrega)
    {
        if (!$this->entregas->contains($entrega)) {
            $this->entregas->add($entrega);
        }
    }

    public function removeEntrega(EntregaBeneficioDetalle $entrega)
    {
        if ($this->entregas->contains($entrega)) {
            $this->entregas->removeElement($entrega);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getEntregas()
    {
        return $this->entregas;
    }

    /**
     * @param ArrayCollection $entregas
     */
    public function setEntregas($entregas)
    {
        $this->entregas = $entregas;
    }
} 
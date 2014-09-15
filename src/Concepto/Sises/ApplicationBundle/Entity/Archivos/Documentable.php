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

namespace Concepto\Sises\ApplicationBundle\Entity\Archivos;

use Concepto\Sises\ApplicationBundle\Entity\OrmPersistible;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Documentable
 * @package Concepto\Sises\ApplicationBundle\Entity\Archivos
 */
abstract class Documentable implements OrmPersistible
{

    /**
     * @var Collection
     */
    protected $archivos;

    function __construct()
    {
        $this->archivos = new ArrayCollection();
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArchivos()
    {
        return $this->archivos;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $archivos
     */
    public function setArchivos($archivos)
    {
        $this->archivos = $archivos;
    }

    /**
     * @param Archivo $archivo
     */
    public function addArchivo($archivo)
    {
        if (!$this->archivos->contains($archivo)) {
            $archivo->setDocumentable($this);
            $this->archivos->add($archivo);
        }
    }

    /**
     * @param Archivo $archivo
     */
    public function removeArchivo($archivo)
    {
        if ($this->archivos->contains($archivo)) {
            $this->archivos->removeElement($archivo);
            $archivo->setDocumentable(null);
        }
    }
}
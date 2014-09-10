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

namespace Concepto\Sises\ApplicationBundle\Entity\Ubicacion;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Class Municipio
 * @package Concepto\Sises\ApplicationBundle\Entity
 * @Entity()
 * @Table(name="ubicacion_municipio")
 */
class Municipio {
    /**
     * @var string
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", length=36)
     */
    protected $id;

    /**
     * @var string
     * @Column(name="codigo_dane", length=5, unique=true, nullable=false)
     */
    protected $codigoDane;

    /**
     * @var string
     * @Column(name="nombre", length=255, unique=true, nullable=false)
     */
    protected $nombre;

    /**
     * @var Departamento
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Ubicacion\Departamento")
     */
    protected $departamento;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCodigoDane()
    {
        return $this->codigoDane;
    }

    /**
     * @param string $codigoDane
     */
    public function setCodigoDane($codigoDane)
    {
        $this->codigoDane = $codigoDane;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return \Concepto\Sises\ApplicationBundle\Entity\Ubicacion\Departamento
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Ubicacion\Departamento $departamento
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }
}
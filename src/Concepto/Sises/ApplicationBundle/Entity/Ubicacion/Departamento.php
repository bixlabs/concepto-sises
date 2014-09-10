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
use Doctrine\ORM\Mapping\Table;

/**
 * Class Departamento
 * @package Concepto\Sises\ApplicationBundle\Entity\Ubicacion
 * @Entity()
 * @Table(name="ubicacion_departamento")
 */
class Departamento {
    /**
     * @var string
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", length=36)
     */
    protected $id;

    /**
     * @var string
     * @Column(name="codigo_dane", length=2, unique=true, nullable=false)
     */
    protected $codigoDane;

    /**
     * @var string
     * @Column(name="nombre", length=255, unique=true, nullable=false)
     */
    protected $nombre;

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
}
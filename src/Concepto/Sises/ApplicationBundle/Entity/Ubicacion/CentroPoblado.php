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
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Class CentroPoblado
 * @package Concepto\Sises\ApplicationBundle\Entity\Ubicacion
 * @Entity()
 * @Table(name="ubicacion_centro_poblado")
 */
class CentroPoblado {
    /**
     * @var string
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", length=36)
     * @Groups({"list", "detalles"})
     */
    protected $id;

    /**
     * @var string
     * @Column(name="codigo_dane", length=10, unique=true, nullable=false)
     */
    protected $codigoDane;

    /**
     * @var  string
     * @Column(name="tipo_dane", length=5)
     */
    protected $tipoDane;

    /**
     * @var string
     * @Column(name="nombre", length=255, nullable=false)
     * @Groups({"list", "detalles"})
     */
    protected $nombre;

    /**
     * @var Municipio
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Ubicacion\Municipio")
     * @Groups({"list"})
     */
    protected $municipio;

    /**
     * @VirtualProperty()
     * @SerializedName("nombre_detallado")
     * @Groups({"list"})
     */
    public function getNombreDetallado()
    {
        return $this->__toString();
    }

    public function __toString()
    {
       if ($this->getNombre() === $this->municipio->getNombre()) {
           return "{$this->municipio->getNombre()}, {$this->municipio->getDepartamento()->getNombre()}";
       }

       return "{$this->nombre} - {$this->municipio->getNombre()}, {$this->municipio->getDepartamento()->getNombre()}";
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
    public function getCodigoDane()
    {
        return $this->codigoDane;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $tipoDane
     */
    public function setTipoDane($tipoDane)
    {
        $this->tipoDane = $tipoDane;
    }

    /**
     * @return string
     */
    public function getTipoDane()
    {
        return $this->tipoDane;
    }

    /**
     * @return \Concepto\Sises\ApplicationBundle\Entity\Ubicacion\Municipio
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Ubicacion\Municipio $municipio
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
    }
}
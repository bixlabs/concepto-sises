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

namespace Concepto\Sises\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class Contrato
 * @package Concepto\Sises\ApplicationBundle\Entity
 * @Entity()
 * @Table(name="contrato")
 * @UniqueEntity(fields={"resolucion"}, message="No pueden existir dos contratos con la misma resolucion")
 */
class Contrato implements OrmPersistible {
    /**
     * @var string
     * @Id()
     * @Column(name="id", length=36)
     * @GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var string
     * @Column(name="nombre", length=250, nullable=false)
     * @NotBlank(message="EL campo 'nombre' no puede estar vacio")
     */
    protected $nombre;

    /**
     * @var string
     * @Column(name="descripcion", length=300, nullable=false)
     * @NotBlank(message="El campo 'descripcion' no puede estar vacio")
     */
    protected $descripcion;

    /**
     * @var string
     * @Column(name="resolucion", length=250, nullable=false, unique=true)
     * @NotBlank(message="El campo 'resolucion' no puede estar vacio")
     */
    protected $resolucion;

    /**
     * @var double
     * @Column(name="valor", type="decimal", precision=64, scale=2)
     */
    protected $valor;

    /**
     * @var Empresa
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Empresa", fetch="LAZY")
     * @NotNull(message="El valor 'empresa' no puede ser nulo")
     * @JoinColumn(nullable=false)
     */
    protected $empresa;

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
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return string
     */
    public function getResolucion()
    {
        return $this->resolucion;
    }

    /**
     * @param string $resolucion
     */
    public function setResolucion($resolucion)
    {
        $this->resolucion = $resolucion;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return \Concepto\Sises\ApplicationBundle\Entity\Empresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Empresa $empresa
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }
}
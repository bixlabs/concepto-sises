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

namespace Concepto\Sises\ApplicationBundle\Entity\Financiera;


use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class Entidad
 * @package Concepto\Sises\ApplicationBundle\Entity\Financiera
 * @Entity()
 * @Table(name="entidad_fianciera")
 */
class Entidad {
    /**
     * @var string
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", length=36)
     * @Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var TipoEntidad
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Financiera\TipoEntidad")
     * @NotNull()
     * @Groups({"list"})
     */
    protected $tipo;

    /**
     * @var
     * @Column(name="nombre", length=250, unique=true)
     * @NotBlank()
     * @Groups({"list", "details"})
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
     * @return TipoEntidad
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param TipoEntidad $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @VirtualProperty()
     * @SerializedName("nombre_detallado")
     * @Groups({"list"})
     * @return string
     */
    public function getNombreDetallado()
    {
        return "{$this->getTipo()->getNombre()} / {$this->nombre}";
    }
}
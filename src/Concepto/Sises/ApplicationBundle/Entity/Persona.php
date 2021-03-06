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
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class Persona
 * @package Concepto\Sises\ApplicationBundle\Entity
 * @Entity()
 * @Table(name="persona")
 * @UniqueEntity(fields={"documento"}, message="No puede existir dos personas con el mismo documento")
 */
class Persona implements OrmPersistible
{
    /**
     * @var string
     * @Id()
     * @Column(name="id", length=36)
     * @GeneratedValue(strategy="UUID")
     * @Groups({"list","details"})
     */
    protected $id;

    /**
     * @var string
     * @Column(name="nombre", length=250, nullable=false)
     * @NotBlank(message="El campo 'nombre' no puede estar vacio")
     * @Groups({"list","details"})
     */
    protected $nombre;

    /**
     * @var string
     * @Column(name="apellidos", length=250, nullable=false)
     * @NotBlank(message="El campo 'apellidos' no puede estar vacio")
     * @Groups({"list","details"})
     */
    protected $apellidos;

    /**
     * @var string
     * @Column(name="documento", length=20, nullable=false, unique=true)
     * @NotBlank(message="El campo 'documento' no puede estar vacio")
     * @Groups({"list","details"})
     */
    protected $documento;

    /**
     * @var string
     * @Column(name="foto", length=255, nullable=true)
     * @Groups({"list", "details"})
     */
    protected $foto;

    /**
     * @VirtualProperty()
     * @SerializedName("nombre_completo")
     * @Groups({"list"})
     */
    public function getNombreCompleto()
    {
        return trim("{$this->nombre} {$this->apellidos}");
    }

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
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * @param string $apellidos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return string
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @param string $documento
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;
    }

    /**
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * @param string $foto
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }
}
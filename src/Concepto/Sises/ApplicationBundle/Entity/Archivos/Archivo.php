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
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class Archivo
 * @package Concepto\Sises\ApplicationBundle\Entity\Archivos
 * @MappedSuperclass()
 */
class Archivo {

    /**
     * @var string
     * @Id()
     * @Column(name="id", length=36)
     * @GeneratedValue(strategy="UUID")
     * @Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var string
     * @Column(name="nombre", length=255)
     * @NotBlank()
     * @Groups({"list", "details"})
     */
    protected $nombre;

    /**
     * @var string
     * @Column(name="file", length=255)
     * @NotBlank()
     * @Groups({"list", "details"})
     */
    protected $file;

    /**
     * @var Documentable
     * @Groups({"list"})
     */
    protected $documentable;

    /**
     * @VirtualProperty()
     * @SerializedName("documentable")
     * @Groups({"details"})
     */
    public function getDocumentableId()
    {
        return $this->documentable->getId();
    }

    /**
     * @return \Concepto\Sises\ApplicationBundle\Entity\Archivos\Documentable
     */
    public function getDocumentable()
    {
        return $this->documentable;
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Archivos\Documentable $documentable
     */
    public function setDocumentable($documentable)
    {
        $this->documentable = $documentable;
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
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }
}
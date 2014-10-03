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
use Doctrine\ORM\Mapping\UniqueConstraint;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class ServicioOperativo
 * @package Concepto\Sises\ApplicationBundle\Entity
 * @Entity()
 * @Table(name="servicio_operativo", uniqueConstraints={
 *     @UniqueConstraint(name="servicio_operativo", columns={"recursoHumano_id", "nombre"})
 * })
 * @UniqueEntity(message="No puede existir un servicio duplicado", fields={"contrato", "nombre"})
 */
class ServicioOperativo {
    /**
     * @var string
     * @Id()
     * @Column(length=36, name="id")
     * @GeneratedValue(strategy="UUID")
     * @Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var string
     * @Column(name="nombre", length=250)
     * @NotBlank()
     * @Groups({"list", "details"})
     */
    protected $nombre;

    /**
     * @var LugarEntrega
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\LugarEntrega")
     * @NotNull()
     * @Groups({"list"})
     */
    protected $lugar;

    /**
     * @var double
     * @Column(name="valor_unitario", type="decimal", precision=64, scale=2)
     * @NotBlank()
     * @Groups({"list", "details"})
     */
    protected $valorUnitario;

    /**
     * @var RecursoHumano
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\RecursoHumano", fetch="LAZY", inversedBy="servicios")
     * @NotNull()
     * @JoinColumn(nullable=false)
     * @Groups({"list"})
     * @MaxDepth(depth=1)
     */
    protected $recursoHumano;

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
     * @return float
     */
    public function getValorUnitario()
    {
        return $this->valorUnitario;
    }

    /**
     * @param float $valorUnitario
     */
    public function setValorUnitario($valorUnitario)
    {
        $this->valorUnitario = $valorUnitario;
    }

    /**
     * @return RecursoHumano
     */
    public function getRecursoHumano()
    {
        return $this->recursoHumano;
    }

    /**
     * @VirtualProperty()
     * @SerializedName("recurso_humano")
     * @Groups({"details"})
     */
    public function getRelatedId()
    {
        if ($this->recursoHumano) {
            return $this->recursoHumano->getId();
        }

        return null;
    }

    /**
     * @param RecursoHumano $recursoHumano
     */
    public function setRecursoHumano($recursoHumano)
    {
        $this->recursoHumano = $recursoHumano;
    }

    /**
     * @return LugarEntrega
     */
    public function getLugar()
    {
        return $this->lugar;
    }

    /**
     * @param LugarEntrega $lugar
     */
    public function setLugar($lugar)
    {
        $this->lugar = $lugar;
    }

    /**
     * @VirtualProperty()
     * @SerializedName("lugar")
     * @Groups({"details"})
     * @return null|string
     */
    public function getLugarId()
    {
        if ($this->lugar) {
            return $this->lugar->getId();
        }

        return null;
    }
}
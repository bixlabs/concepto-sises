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
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class ServicioContratado
 * @package Concepto\Sises\ApplicationBundle\Entity
 * @Entity()
 * @Table(name="servicio_contratado", uniqueConstraints={
 *     @UniqueConstraint(name="servicio_contrato", columns={"contrato_id", "nombre"})
 * })
 * @UniqueEntity(message="No puede existir un servicio duplicado", fields={"contrato", "nombre"})
 */
class ServicioContratado {
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
     * @var int
     * @Column(name="dias_contratados", type="integer")
     * @NotBlank()
     * @Groups({"list", "details"})
     */
    protected $diasContratados;

    /**
     * @var int
     * @Column(name="unidades_diarias", type="integer")
     * @NotBlank()
     * @Groups({"list", "details"})
     */
    protected $unidadesDiarias;

    /**
     * @var double
     * @Column(name="valor_unitario", type="decimal", precision=64, scale=2)
     * @NotBlank()
     * @Groups({"list", "details"})
     */
    protected $valorUnitario;

    /**
     * @var double
     * @Column(name="costo_unitario", type="decimal", precision=64, scale=2)
     * @NotBlank()
     * @Groups({"list", "details"})
     */
    protected $costoUnitario;

    /**
     * @var Contrato
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Contrato", fetch="LAZY", inversedBy="servicios")
     * @NotNull()
     * @JoinColumn(nullable=false)
     * @Groups({"list"})
     * @MaxDepth(depth=1)
     */
    protected $contrato;

    /**
     * @VirtualProperty()
     * @SerializedName("nombre_detallado")
     * @Groups({"list"})
     */
    public function getNombreDetallado()
    {
        return "{$this->nombre} - {$this->contrato->getNombre()}";
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
     * @param float $costoUnitario
     */
    public function setCostoUnitario($costoUnitario)
    {
        $this->costoUnitario = $costoUnitario;
    }

    /**
     * @return float
     */
    public function getCostoUnitario()
    {
        return $this->costoUnitario;
    }

    /**
     * @param int $diasContratados
     */
    public function setDiasContratados($diasContratados)
    {
        $this->diasContratados = $diasContratados;
    }

    /**
     * @return int
     */
    public function getDiasContratados()
    {
        return $this->diasContratados;
    }

    /**
     * @param int $unidadesDiarias
     */
    public function setUnidadesDiarias($unidadesDiarias)
    {
        $this->unidadesDiarias = $unidadesDiarias;
    }

    /**
     * @return int
     */
    public function getUnidadesDiarias()
    {
        return $this->unidadesDiarias;
    }

    /**
     * @param mixed $valorUnitario
     */
    public function setValorUnitario($valorUnitario)
    {
        $this->valorUnitario = $valorUnitario;
    }

    /**
     * @return mixed
     */
    public function getValorUnitario()
    {
        return $this->valorUnitario;
    }

    /**
     * @return mixed
     */
    public function getContrato()
    {
        return $this->contrato;
    }

    /**
     * @param mixed $contrato
     */
    public function setContrato($contrato)
    {
        $this->contrato = $contrato;
    }

    /**
     * @VirtualProperty()
     * @SerializedName("contrato")
     * @Groups({"details"})
     */
    public function getRelatedId()
    {
        /** @var OrmPersistible  */
        if ($this->contrato) {
            return $this->contrato->getId();
        }

        return null;
    }

}
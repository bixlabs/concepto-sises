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

namespace Concepto\Sises\ApplicationBundle\Entity\Entrega;


use Concepto\Sises\ApplicationBundle\Entity\Beneficiario;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups;

/**
 * Class EntregaBeneficioDetalle
 * @package Concepto\Sises\ApplicationBundle\Entity\Entrega
 * @Entity()
 * @Table(name="entrega_beneficio_detalle")
 */
class EntregaBeneficioDetalle {

    /**
     * @var string
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", length=36)
     * @Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var EntregaBeneficio
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaBeneficio")
     */
    protected $entregaBeneficio;

    /**
     * @var Beneficiario
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Beneficiario")
     */
    protected $beneficiario;

    /**
     * @var bool
     * @Column(type="boolean")
     */
    protected $estado = false;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return EntregaBeneficio
     */
    public function getEntregaBeneficio()
    {
        return $this->entregaBeneficio;
    }

    /**
     * @param EntregaBeneficio $entregaBeneficio
     */
    public function setEntregaBeneficio($entregaBeneficio)
    {
        $this->entregaBeneficio = $entregaBeneficio;
    }

    /**
     * @return Beneficiario
     */
    public function getBeneficiario()
    {
        return $this->beneficiario;
    }

    /**
     * @param Beneficiario $beneficiario
     */
    public function setBeneficiario($beneficiario)
    {
        $this->beneficiario = $beneficiario;
    }

    /**
     * @return boolean
     */
    public function isEstado()
    {
        return $this->estado;
    }

    /**
     * @param boolean $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
} 
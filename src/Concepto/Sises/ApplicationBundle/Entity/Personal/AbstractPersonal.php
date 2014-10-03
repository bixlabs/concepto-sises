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

namespace Concepto\Sises\ApplicationBundle\Entity\Personal;

use Concepto\Sises\ApplicationBundle\Entity\Archivos\Documentable;
use Concepto\Sises\ApplicationBundle\Entity\Contrato;
use Concepto\Sises\ApplicationBundle\Entity\Financiera\Entidad;
use Concepto\Sises\ApplicationBundle\Entity\OrmPersistible;
use Concepto\Sises\ApplicationBundle\Entity\Persona;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\MappedSuperclass;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class AbstractPersonal
 * @package Concepto\Sises\ApplicationBundle\Entity\Personal
 * @MappedSuperclass()
 */
abstract class AbstractPersonal extends Documentable implements OrmPersistible
{

    /**
     * @var string
     * @Id()
     * @Column(name="id", length=36)
     * @GeneratedValue(strategy="UUID")
     * @Groups({"list", "details"})
     */
    protected $id;

    protected $contrato;

    /**
     * @var Persona
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Persona")
     * @NotNull()
     * @Groups({"list"})
     */
    protected $persona;

    /**
     * @var Entidad
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Financiera\Entidad")
     * @Groups({"list"})
     */
    protected $entidadFinanciera;

    /**
     * @var string
     * @Column(name="numero_cuenta", length=250, nullable=true)
     * @Groups({"details"})
     */
    protected $numeroCuenta;

    /**
     * @var string
     * @Column(name="observaciones_financieras", type="text", nullable=true)
     */
    protected $observacionesFinancieras;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Contrato
     */
    public function getContrato()
    {
        return $this->contrato;
    }

    /**
     * @param Contrato $contrato
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
    public function getContratoId()
    {
        if ($this->contrato) {
            return $this->contrato->getId();
        }

        return null;
    }

    /**
     * @return Persona
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * @param Persona $persona
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;
    }

    /**
     * @VirtualProperty()
     * @SerializedName("persona")
     * @Groups({"details"})
     */
    public function getPersonaId()
    {
        if ($this->persona) {
            return $this->persona->getId();
        }

        return null;
    }

    /**
     * @return Entidad
     */
    public function getEntidadFinanciera()
    {
        return $this->entidadFinanciera;
    }

    /**
     * @param Entidad $entidadFinanciera
     */
    public function setEntidadFinanciera($entidadFinanciera)
    {
        $this->entidadFinanciera = $entidadFinanciera;
    }

    /**
     * @return string
     */
    public function getNumeroCuenta()
    {
        return $this->numeroCuenta;
    }

    /**
     * @param string $numeroCuenta
     */
    public function setNumeroCuenta($numeroCuenta)
    {
        $this->numeroCuenta = $numeroCuenta;
    }

    /**
     * @return string
     */
    public function getObservacionesFinancieras()
    {
        return $this->observacionesFinancieras;
    }

    /**
     * @param string $observacionesFinancieras
     */
    public function setObservacionesFinancieras($observacionesFinancieras)
    {
        $this->observacionesFinancieras = $observacionesFinancieras;
    }
}
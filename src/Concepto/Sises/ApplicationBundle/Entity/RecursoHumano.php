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


use Concepto\Sises\ApplicationBundle\Entity\Archivos\Documentable;
use Concepto\Sises\ApplicationBundle\Entity\Financiera\Entidad as EntidadFinanciera;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class RecursoHumano
 * @package Concepto\Sises\ApplicationBundle\Entity
 * @Entity()
 * @Table(name="recurso_humano")
 */
class RecursoHumano extends Documentable implements OrmPersistible
{
    /**
     * @var string
     * @Id()
     * @Column(name="id", length=36)
     * @GeneratedValue(strategy="UUID")
     * @Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var Contrato
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Contrato", fetch="LAZY")
     * @NotBlank()
     * @JoinColumn(nullable=false)
     * @Groups({"list"})
     */
    protected $contrato;

    /**
     * @var CargoOperativo
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\CargoOperativo")
     * @NotNull()
     * @Groups({"list"})
     */
    protected $cargo;

    /**
     * @var Persona
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Persona")
     * @NotNull()
     * @Groups({"list"})
     */
    protected $persona;

    /**
     * @var EntidadFinanciera
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
     * @{inheridoc}
     * @OneToMany(
     *  targetEntity="Concepto\Sises\ApplicationBundle\Entity\Archivos\ArchivoRecursoHumano",
     *  mappedBy="documentable",
     *  cascade={"persist"}
     * )
     */
    protected $archivos;

    /**
     * @var Collection
     * @OneToMany(
     *      targetEntity="Concepto\Sises\ApplicationBundle\Entity\ServicioOperativo",
     *      mappedBy="recursoHumano",
     *      cascade={"persist"}
     * )
     * @Groups({"details"})
     */
    protected $servicios;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return CargoOperativo
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * @param CargoOperativo $cargo
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
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
     * @return EntidadFinanciera
     */
    public function getEntidadFinanciera()
    {
        return $this->entidadFinanciera;
    }

    /**
     * @param EntidadFinanciera $entidadFinanciera
     */
    public function setEntidadFinanciera($entidadFinanciera)
    {
        $this->entidadFinanciera = $entidadFinanciera;
    }

    /**
     * @VirtualProperty()
     * @SerializedName("cargo")
     * @Groups({"details"})
     */
    public function getCargoId()
    {
        if ($this->cargo) {
            return $this->cargo->getId();
        }

        return null;
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
     * @VirtualProperty()
     * @SerializedName("entidad_financiera")
     * @Groups({"details"})
     */
    public function getEntidadId()
    {
        if ($this->entidadFinanciera) {
            return $this->entidadFinanciera->getId();
        }

        return null;
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

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServicios()
    {
        return $this->servicios;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $servicios
     */
    public function setServicios($servicios)
    {
        $this->servicios = $servicios;
    }

    /**
     * @param ServicioOperativo $servicio
     */
    public function addServicio($servicio)
    {
        if (!$this->servicios->contains($servicio)) {
            $servicio->setRecursoHumano($this);
            $this->servicios->add($servicio);
        }
    }

    /**
     * @param ServicioOperativo $servicio
     */
    public function removeServicio($servicio) {
        if ($this->servicios->contains($servicio)) {
            $this->servicios->removeElement($servicio);
            $servicio->setRecursoHumano(null);
        }
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
}
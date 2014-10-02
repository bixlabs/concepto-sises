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
     * @var Empresa
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Empresa", fetch="LAZY")
     * @NotBlank()
     * @JoinColumn(nullable=false)
     * @Groups({"list"})
     */
    protected $empresa;

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
     * @{inheridoc}
     * @OneToMany(
     *  targetEntity="Concepto\Sises\ApplicationBundle\Entity\Archivos\ArchivoRecursoHumano",
     *  mappedBy="documentable",
     *  cascade={"persist"}
     * )
     */
    protected $archivos;

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
     * @return Empresa
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param Empresa $empresa
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @VirtualProperty()
     * @SerializedName("empresa")
     * @Groups({"details"})
     */
    public function getEmpresaId()
    {
        if ($this->empresa) {
            return $this->empresa->getId();
        }

        return null;
    }
}
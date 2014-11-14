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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class Beneficiario
 * @package Concepto\Sises\ApplicationBundle\Entity
 * @Entity()
 * @Table(name="beneficiario")
 */
class Beneficiario extends Documentable implements OrmPersistible {
    /**
     * @var string
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", length=36)
     * @Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var Persona
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Persona")
     * @NotNull()
     * @Groups({"list"})
     */
    protected $persona;

    /**
     * @var Contrato
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Contrato", fetch="LAZY")
     * @NotNull()
     * @JoinColumn(nullable=false)
     * @Groups({"list"})
     */
    protected $contrato;

    /**
     * @var Collection
     * @OneToMany(
     *  targetEntity="Concepto\Sises\ApplicationBundle\Entity\Beneficio",
     *  mappedBy="beneficiario",
     *  cascade={"persist"}
     * )
     * @Groups({"details", "list"})
     * @MaxDepth(depth=3)
     */
    protected $beneficios;

    /**
     * @{inheridoc}
     * @OneToMany(
     *  targetEntity="Concepto\Sises\ApplicationBundle\Entity\Archivos\ArchivoBeneficiario",
     *  mappedBy="documentable",
     *  cascade={"persist"}
     * )
     * @Exclude()
     */
    protected $archivos;

    function __construct()
    {
        parent::__construct();
        $this->beneficios = new ArrayCollection();
    }

    /**
     * @VirtualProperty()
     * @SerializedName("persona")
     * @Groups({"details"})
     */
    public function getPersonaId()
    {
        return $this->persona->getId();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Concepto\Sises\ApplicationBundle\Entity\Persona
     */
    public function getPersona()
    {
        return $this->persona;
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Persona $persona
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBeneficios()
    {
        return $this->beneficios;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $beneficios
     */
    public function setBeneficios($beneficios)
    {
        $this->beneficios = $beneficios;
    }

    /**
     * @param Beneficio $beneficio
     */
    public function addBeneficio($beneficio)
    {
        if (!$this->beneficios->contains($beneficio)) {
            $beneficio->setBeneficiario($this);
            $this->beneficios->add($beneficio);
        }
    }

    /**
     * @param Beneficio $beneficio
     */
    public function removeBeneficio($beneficio)
    {
        if ($this->beneficios->contains($beneficio)) {
            $beneficio->setBeneficiario(null);
            $this->beneficios->removeElement($beneficio);
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
     * @return string
     * @VirtualProperty()
     * @SerializedName("contrato")
     * @Groups({"details"})
     */
    public function getContratoId()
    {
        return $this->contrato->getId();
    }
}
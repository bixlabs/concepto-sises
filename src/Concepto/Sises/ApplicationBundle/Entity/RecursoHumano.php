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
use Concepto\Sises\ApplicationBundle\Entity\Personal\AbstractPersonal;
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
class RecursoHumano extends AbstractPersonal
{
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
}
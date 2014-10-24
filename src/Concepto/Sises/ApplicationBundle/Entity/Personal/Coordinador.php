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


use Concepto\Sises\ApplicationBundle\Entity\Contrato;
use Concepto\Sises\ApplicationBundle\Entity\CoordinadorAsignacion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class Coordinador
 * @package Concepto\Sises\ApplicationBundle\Entity\Personal
 * @Entity()
 * @Table(name="recurso_humano_coordinador")
 */
class Coordinador extends AbstractPersonal
{
    /**
     * @{inheridoc}
     * @OneToMany(
     *  targetEntity="Concepto\Sises\ApplicationBundle\Entity\Archivos\ArchivoCoordinador",
     *  mappedBy="documentable",
     *  cascade={"persist"}
     * )
     * @Exclude()
     */
    protected $archivos;

    /**
     * @var Contrato
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Contrato", fetch="LAZY")
     * @NotBlank()
     * @JoinColumn(nullable=false)
     * @Groups({"list"})
     */
    protected $contrato;

    /**
     * @var Collection
     * @OneToMany(
     *      targetEntity="Concepto\Sises\ApplicationBundle\Entity\CoordinadorAsignacion",
     *      mappedBy="coordinador",
     *      cascade={"persist"}
     * )
     * @Groups({"details"})
     * @MaxDepth(depth=2)
     */
    protected $asignacion;

    function __construct()
    {
        parent::__construct();
        $this->asignacion = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getAsignacion()
    {
        return $this->asignacion;
    }

    /**
     * @param Collection $asignacion
     */
    public function setAsignacion($asignacion)
    {
        $this->asignacion = $asignacion;
    }

    /**
     * @param CoordinadorAsignacion $asignacion
     */
    public function addAsignacion($asignacion)
    {
        if (!$this->asignacion->contains($asignacion)) {
            $asignacion->setCoordinador($this);
            $this->asignacion->add($asignacion);
        }
    }

    /**
     * @param CoordinadorAsignacion $asignacion
     */
    public function removeAsignacion($asignacion)
    {
        if ($this->asignacion->contains($asignacion)) {
            $asignacion->setCoordinador(null);
            $this->asignacion->remove($asignacion);
        }
    }
}
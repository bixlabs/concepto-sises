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

use Concepto\Sises\ApplicationBundle\Entity\CoordinadorAsignacion;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Class EntregaAsignacion
 * @package Concepto\Sises\ApplicationBundle\Entity\Entrega
 * @Entity()
 * @Table("entrega_asignacion")
 */
class EntregaAsignacion extends Entrega
{
    /**
     * @var CoordinadorAsignacion
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\CoordinadorAsignacion")
     * @Groups({"list"})
     */
    protected $asignacion;

    /**
     * @var bool
     * @Column(name="is_manual", type="boolean")
     */
    protected $isCierreManual;

    /**
     * @VirtualProperty()
     * @SerializedName("asignacion")
     * @Groups({"details"})
     *
     * @return string
     */
    public function getAsignacionId()
    {
        return $this->getAsignacion()->getId();
    }

    /**
     * Set asignacion
     *
     * @param CoordinadorAsignacion $asignacion
     *
     * @return EntregaAsignacion
     */
    public function setAsignacion(CoordinadorAsignacion $asignacion = null)
    {
        $this->asignacion = $asignacion;

        return $this;
    }

    /**
     * Get asignacion
     *
     * @return CoordinadorAsignacion
     */
    public function getAsignacion()
    {
        return $this->asignacion;
    }
    /**
     * Set isCierreManual
     *
     * @param boolean $isCierreManual
     *
*@return EntregaAsignacion
     */
    public function setIsCierreManual($isCierreManual)
    {
        $this->isCierreManual = $isCierreManual;

        return $this;
    }

    /**
     * Get isCierreManual
     *
     * @return boolean
     */
    public function getIsCierreManual()
    {
        return $this->isCierreManual;
    }
}

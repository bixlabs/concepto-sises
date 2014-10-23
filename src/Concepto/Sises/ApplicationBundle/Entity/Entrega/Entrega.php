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


use Concepto\Sises\ApplicationBundle\Entity\Contrato;
use Concepto\Sises\ApplicationBundle\Validator\Constraints\EntregaNotOverlayDates;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class Entrega
 * @package Concepto\Sises\ApplicationBundle\Entity\Entrega
 * @Entity()
 * @Table(name="entrega_entrega")
 * @EntregaNotOverlayDates()
 */
class Entrega extends EntregaBase
{
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
     * @return string
     */
    public function getContratoId()
    {
        return $this->getContrato()->getId();
    }
}

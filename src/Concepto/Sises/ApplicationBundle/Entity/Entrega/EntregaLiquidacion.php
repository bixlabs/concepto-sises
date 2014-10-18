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


use Concepto\Sises\ApplicationBundle\Entity\ServicioContratado;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class EntregaLiquidacion
 * @package Concepto\Sises\ApplicationBundle\Entity\Entrega
 * @Entity()
 * @Table(name="entrega_liquidacion")
 */
class EntregaLiquidacion {
    /**
     * @var string
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", length=36)
     * @Groups({"list", "details"})
     */
    protected $id;

    /**
     * @var Entrega
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega")
     * @NotNull()
     */
    protected $entrega;

    /**
     * @var ServicioContratado
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\ServicioContratado")
     * @NotNull()
     * @Groups({"list"})
     */
    protected $servicio;

    /**
     * @var int
     * @Column(name="liquidado", type="integer", nullable=false)
     * @NotNull()
     */
    protected $totalLiquidado;
}
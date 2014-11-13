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


use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * Class EntregaLiquidacion
 * @package Concepto\Sises\ApplicationBundle\Entity\Entrega
 * @Entity()
 * @Table(name="entrega_liquidacion")
 */
class EntregaLiquidacion extends Entrega
{
    /**
     * @var Collection
     * @OneToMany(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaOperacion", mappedBy="liquidacion")
     */
    protected $detalles;
} 
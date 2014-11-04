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


use Concepto\Sises\ApplicationBundle\Entity\EntityRepository;

class EntregaOperacionRepository extends EntityRepository
{
    /**
     * @param Entrega $entrega
     * @return mixed Devuelve las EntregaOperacion en el rango de fechas de la Entrega
     */
    public function getByEntrega(Entrega $entrega)
    {
        return $this->createQueryBuilder('eo')
            ->andWhere('eo.fechaEntrega >= :fechaInicio')
            ->andWhere('eo.fechaEntrega <= :fechaCierre')
            ->setParameter('fechaInicio', $entrega->getFechaInicio())
            ->setParameter('fechaCierre', $entrega->getFechaCierre())
            ->getQuery()->execute()
        ;
    }
} 
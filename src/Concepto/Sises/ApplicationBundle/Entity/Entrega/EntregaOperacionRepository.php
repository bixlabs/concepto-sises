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
    public function getByEntrega(Entrega $entrega, $servicio = null)
    {
        $qb = $this->createQueryBuilder('eo')
            ->andWhere('eo.fechaEntrega >= :fechaInicio')
            ->andWhere('eo.fechaEntrega <= :fechaCierre')
            ->setParameter('fechaInicio', $entrega->getFechaInicio())
            ->setParameter('fechaCierre', $entrega->getFechaCierre());

        if ($servicio) {
            $qb->andWhere('eo.servicio = :servicio')
                ->setParameter('servicio', $servicio);
        }

        $qb
            ->getQuery()->execute()
        ;
    }

    /**
     * @param EntregaLiquidacion|string $liquidacion
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function calcularDetalle($liquidacion)
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.servicio', 's')
            ->leftJoin('s.recursoHumano', 'rh')
            ->leftJoin('rh.cargo', 'c')
            ->leftJoin('rh.persona', 'p')

            ->andWhere('d.liquidacion = :liquidacion')
            ->setParameter('liquidacion', $liquidacion)

            ->select(
                'p.documento',
                'p.nombre',
                'p.apellidos',
                'c.nombre as cargo',
                's.nombre as servicio_nombre',
                's.id', 'SUM(d.cantidad) as cantidad', 's.valorUnitario')
            ->groupBy('s.id')
            ->getQuery()->execute()
            ;
    }
} 
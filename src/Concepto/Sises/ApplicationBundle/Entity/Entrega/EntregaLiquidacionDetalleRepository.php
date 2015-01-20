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

class EntregaLiquidacionDetalleRepository extends EntityRepository
{
    /**
     * @param EntregaLiquidacion|string $liquidacion
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function calcularDetalle($liquidacion)
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.servicio', 's')
            ->leftJoin('s.recursoHumano', 'rh')
            ->leftJoin('rh.entidadFinanciera', 'ef')
            ->leftJoin('rh.cargo', 'c')
            ->leftJoin('rh.persona', 'p')

            ->andWhere('d.liquidacion = :liquidacion')
            ->setParameter('liquidacion', $liquidacion)

            ->select(
                'p.documento',
                'p.nombre',
                'p.apellidos',
                'c.nombre as cargo',
                'rh.numeroCuenta as cuenta',
                'rh.observacionesFinancieras as cuenta_observaciones',
                'ef.nombre as entidad',
                's.nombre as servicio_nombre',
                's.id as servicio', 'SUM(d.cantidad) as cantidad', 's.valorUnitario')
            ->groupBy('s.id')
            ->getQuery()->execute()
            ;
    }
} 
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

class EntregaBeneficioDetalleRepository extends EntityRepository
{
    public function calcular($entregaId, $estado = true)
    {
        $completeResults = $this
            ->getEntityManager()->getRepository('SisesApplicationBundle:Entrega\EntregaAsignacion')
            ->createQueryBuilder('ea')
            ->select('s.id','s.nombre', '0 as total')
            ->leftJoin('ea.entrega', 'e')
            ->leftJoin('ea.asignacion', 'a')
            ->leftJoin('a.servicio', 's')
            ->andWhere('e = :id')
            ->setParameters(array(
                'id' => $entregaId
            ))->getQuery()->execute();

        $completeIndexed = array();

        foreach ($completeResults as $completeResult) {
            $completeIndexed[$completeResult['id']] = $completeResult;
        }

        $results = $this->baseCalcular($entregaId, $estado)->getQuery()->execute();

        foreach ($results as $result) {
            $completeIndexed[$result['id']] = $result;
        }

        return array_values($completeIndexed);
    }

    public function calcularDetalle($entregaId, $estado = true)
    {
        return $this->baseCalcular($entregaId, $estado)
            ->leftJoin('ea.asignacion', 'a')
            ->leftJoin('a.coordinador', 'c')
            ->leftJoin('c.persona', 'p')
            ->select(
                'p.nombre as coordinador_nombre',
                'p.apellidos as coordinador_apellidos',
                's.nombre as servicio_nombre',
                'COUNT(s) as servicio_total')
            ->groupBy('c.id', 's.id')
            ->getQuery()->execute();
    }

    private function baseCalcular($entregaId, $estado)
    {
        return $this
            ->createQueryBuilder('d')
            ->select('s.id','s.nombre', 'COUNT(s) as total')
            ->leftJoin('d.entregaBeneficio', 'eb')
            ->leftJoin('eb.servicio', 's')
            ->leftJoin('eb.entrega', 'ea')
            ->leftJoin('ea.entrega', 'e')
            ->groupBy('s.id')
            ->andWhere('e = :id')
            ->andWhere('d.estado = :estado')
            ->setParameters(array(
                'id' => $entregaId,
                'estado' => $estado
            ));
    }

} 
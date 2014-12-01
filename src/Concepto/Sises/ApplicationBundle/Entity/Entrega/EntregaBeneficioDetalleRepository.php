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
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;

class EntregaBeneficioDetalleRepository extends EntityRepository
{
    public function calcular($entregaId, $estado = true)
    {
        $qb = $this->getEntityManager()
            ->getRepository('SisesApplicationBundle:ServicioContratado')
            ->createQueryBuilder('s')
            ->select('s.id as servicio', 's.nombre')
            ->addSelect("($this->countEntregas) as total")
            ->setParameters(array(
                'entrega_id' => $entregaId,
                'estado' => $estado
            ))
            ->groupBy('s.id')
            ->leftJoin('SisesApplicationBundle:Entrega\EntregaBeneficio', 'eb', Join::WITH, 'eb.servicio = s.id');

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
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

    private $countEntregas = <<<DQL
SELECT
    COUNT(_s)
FROM
    SisesApplicationBundle:Entrega\EntregaBeneficioDetalle _d
        LEFT JOIN _d.entregaBeneficio _eb
        LEFT JOIN _eb.servicio _s
        LEFT JOIN _eb.entrega _ea
        LEFT JOIN _ea.entrega _e
WHERE _e = :entrega_id
    AND _d.estado = :estado
    AND _s = s
DQL;

} 
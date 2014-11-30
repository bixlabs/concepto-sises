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

class EntregaAsignacionRepository extends EntityRepository
{
    public function findAllQueryBuilder(array $parameters)
    {
        $qb = parent::findAllQueryBuilder($parameters);

        // Se asegura de solo mostrar las asignaciones abiertas
        $alias = $this->findJoinAlias($qb, 'entrega');
        $qb->where("{$alias}.estado = :estado")->setParameter('estado', Entrega::OPEN);

        // Se asegura que no se vean entregas despues de la fecha de cierre + dias de gracia
        $qb->andWhere(":now <= DATE_ADD({$alias}.fechaCierre, {$alias}.diasGracia, 'day')");

        // Se asegura que no se vean entregas antes de la fecha para que fueron creadas
        $qb->andWhere("{$alias}.fechaInicio <= :now");

        $qb->setParameter('now', new \DateTime());

        return $qb;
    }

} 
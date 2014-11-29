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

class EntregaLiquidacionRepository extends EntityRepository
{
    const ONLY_OPEN = '##restricted';

    public function findAllQueryBuilder(array $parameters)
    {
        $restricted = false;

        if (isset($parameters[self::ONLY_OPEN])) {
            $restricted = true;
            unset($parameters[self::ONLY_OPEN]);
        }

        $qb = parent::findAllQueryBuilder($parameters);
        $alias = $qb->getRootAliases()[0];

        if ($restricted) {
            // Se asegura de solo mostrar las asignaciones abiertas

            $qb->where("{$alias}.estado = :estado")->setParameter('estado', Entrega::OPEN);

            // Se asegura que no se vean entregas despues de la fecha de cierre + dias de gracia
            $qb->andWhere("CURRENT_DATE() <= DATE_ADD({$alias}.fechaCierre, {$alias}.diasGracia, 'day')");

            // Se asegura que no se vean entregas antes de la fecha para que fueron creadas
            $qb->andWhere("{$alias}.fechaInicio >= CURRENT_DATE()");
        }

        return $qb;
    }
} 
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
use Concepto\Sises\ApplicationBundle\Model\EntregaBeneficioQuery;

class EntregaBeneficioRepository extends EntityRepository
{
    public function fechaEntrega(EntregaBeneficioQuery $query)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.fechaEntrega = :fecha')
            ->andWhere('a.entrega = :entrega')
            ->setParameters(array(
                'fecha' => $query->getFecha(),
                'entrega' => $query->getId()
            ))
            // Evita las subconsultas por cada EntregaBeneficio
            ->leftJoin('a.beneficio', 'b')
            ->leftJoin('b.beneficiario', 'bb')
            ->leftJoin('bb.persona', 'p')

            ->leftJoin('b.servicio', 's')

            ->leftJoin('b.lugar', 'l')
            ->leftJoin('l.ubicacion', 'u')
            ->leftJoin('u.municipio', 'm')
            ->leftJoin('m.departamento', 'd')

            ->getQuery()->execute()
        ;
    }
} 
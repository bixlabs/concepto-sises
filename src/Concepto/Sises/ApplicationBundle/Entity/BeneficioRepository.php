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

namespace Concepto\Sises\ApplicationBundle\Entity;


class BeneficioRepository extends EntityRepository
{
    public function getPersonasDeAsignacion(CoordinadorAsignacion $coordinadorAsignacion)
    {
        return $this->createQueryBuilder('b')
            ->select(
                'bb.id as beneficiario',
                'p.id', 'p.documento', 'p.nombre', 'p.apellidos'
            )
            ->leftJoin('b.beneficiario', 'bb')
            ->leftJoin('bb.persona', 'p')
            ->andWhere('b.servicio = :servicio')
            ->andWhere('b.lugar = :lugar')
            ->orderBy('p.apellidos')
            ->orderBy('p.nombre')
            ->setParameters(array(
                'lugar' => $coordinadorAsignacion->getLugar(),
                'servicio' => $coordinadorAsignacion->getServicio()
            ))
            ->getQuery()->execute()
            ;
    }
} 
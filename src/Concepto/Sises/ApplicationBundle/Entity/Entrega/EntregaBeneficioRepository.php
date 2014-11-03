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
use Concepto\Sises\ApplicationBundle\Model\EntregaRealizada;

class EntregaBeneficioRepository extends EntityRepository
{
    public function realizarEntrega(EntregaRealizada $realizada)
    {
        $ids = array();

        foreach($realizada->getEntregas() as $e) {
            $ids[$e->getId()] = $e->isEstado();
        }

        $em = $this->getEntityManager();

        $qb = $em->getRepository('SisesApplicationBundle:Entrega\EntregaBeneficioDetalle')
            ->createQueryBuilder('d');

        $entregas = $qb
            ->where($qb->expr()->in('d.id', array_keys($ids)))
            ->getQuery()->execute();

        foreach($entregas as $entrega) {
            if (isset($ids[$entrega->getId()])) {
                $entrega->setEstado($ids[$entrega->getId()]);
                $em->persist($entrega);
            }
        }
        $em->flush();

        return $entregas;
    }

    public function fechaEntrega(EntregaBeneficioQuery $query)
    {
        return $this->getEntityManager()
            ->getRepository('SisesApplicationBundle:Entrega\EntregaBeneficioDetalle')
            ->createQueryBuilder('detalle')

            ->leftJoin('detalle.beneficiario', 'b')
            ->leftJoin('b.persona', 'p')

            ->leftJoin('detalle.entregaBeneficio', 'eb')
            ->andWhere('eb.entrega = :entrega')
            ->andWhere('eb.fechaEntrega = :fecha')
            ->setParameters(array(
                'entrega' => $query->getId(),
                'fecha' => $query->getFecha(),
            ))

            ->addOrderBy('p.apellidos')
            ->addOrderBy('p.nombre')

            ->getQuery()->execute();
    }
} 
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

namespace Concepto\Sises\ApplicationBundle\EventListener;


use Concepto\Sises\ApplicationBundle\Entity\CoordinadorAsignacion;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaAsignacion;
use Concepto\Sises\ApplicationBundle\Entity\ServicioContratado;
use Doctrine\ORM\Event\LifecycleEventArgs;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;

/**
 * Class EntregaListener
 * @package Concepto\Sises\ApplicationBundle\EventListener
 * @Service(id="concepto.entrega.listener")
 * @Tag(name="doctrine.event_listener", attributes={"event": "prePersist"})
 */
class EntregaListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Entrega) {
            $this->createEntregaAsignacion($entity, $args);
        }
    }

    /**
     * @param Entrega $entity
     * @param LifecycleEventArgs $args
     */
    private function createEntregaAsignacion($entity, $args)
    {
        $em = $args->getEntityManager();
        $servicios = [];

        foreach($entity->getContrato()->getServicios() as $servicio) {
            $servicios[] = $servicio->getId();
        }

        if (count($servicios) > 0) {
            /** @var ServicioContratado $servicio */
            $asignaciones = $em
                ->getRepository('SisesApplicationBundle:CoordinadorAsignacion')
                ->findAll(array('servicio' => 'A,' . implode(';', $servicios)));

            /** @var CoordinadorAsignacion $Asignacion */
            foreach($asignaciones as $asignacion) {
                $eAsignacion = new EntregaAsignacion();
                $eAsignacion->setEntrega($entity);
                $eAsignacion->setAsignacion($asignacion);
                $em->persist($eAsignacion);
            }
        }
    }
} 
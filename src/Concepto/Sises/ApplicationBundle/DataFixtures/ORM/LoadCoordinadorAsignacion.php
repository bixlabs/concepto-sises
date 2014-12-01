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

namespace Concepto\Sises\ApplicationBundle\DataFixtures\ORM;


use Concepto\Sises\ApplicationBundle\Entity\CoordinadorAsignacion;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCoordinadorAsignacion implements FixtureInterface, OrderedFixtureInterface
{

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 290;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $lugar = $manager->getRepository('SisesApplicationBundle:LugarEntrega')->findOneBy(array(
            'nombre' => 'INST. EDU. TRUJILLO'
        ));

        $lugar2 = $manager->getRepository('SisesApplicationBundle:LugarEntrega')->findOneBy(array(
            'nombre' => 'INST. EDU. NORMAL SUPERIOR MARIA INMACULADA'
        ));

        $servicios = $manager->getRepository('SisesApplicationBundle:ServicioContratado')->findAll();
        $coordinador = $manager->getRepository('SisesApplicationBundle:Personal\Coordinador')->findOneBy(array());

        foreach ($servicios as $servicio) {
            $asignacion = new CoordinadorAsignacion();
            $asignacion->setCoordinador($coordinador);
            $asignacion->setLugar($lugar);
            $asignacion->setServicio($servicio);
            $manager->persist($asignacion);

            $asignacion2 = new CoordinadorAsignacion();
            $asignacion2->setCoordinador($coordinador);
            $asignacion2->setLugar($lugar2);
            $asignacion2->setServicio($servicio);
            $manager->persist($asignacion2);
        }

        $manager->flush();
    }
}
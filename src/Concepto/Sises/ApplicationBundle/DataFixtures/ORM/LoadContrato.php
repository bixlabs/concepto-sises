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


use Concepto\Sises\ApplicationBundle\Entity\Contrato;
use Concepto\Sises\ApplicationBundle\Entity\ServicioContratado;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadContrato implements FixtureInterface, OrderedFixtureInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $empresas = $manager->getRepository('SisesApplicationBundle:Empresa')->findAll();

        $empresa = $empresas[0];
        $contratante = $empresas[1];


        $contrato = new Contrato();
        $contrato->setEmpresa($empresa);
        $contrato->setContratante($contratante);
        $contrato->setNombre("Contrato de alimentos de {$empresa->getNombre()}");
        $contrato->setDescripcion($contrato->getNombre());
        $contrato->setResolucion("Resolucion 00" . uniqid() . " de 2014");
        $contrato->setFechaInicio(new \DateTime());
        $contrato->setFechaCierre((new \DateTime())->add(new \DateInterval('P70D')));

        $contrato->setValor(1500000);

        $servicio = new ServicioContratado();
        $servicio->setNombre("Almuerzos");
        $servicio->setDiasContratados(100);
        $servicio->setUnidadesDiarias(1500);
        $servicio->setValorUnitario(2560);
        $servicio->setCostoUnitario(1850);

        $contrato->addServicio($servicio);

        $manager->persist($contrato);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 200;
    }
}
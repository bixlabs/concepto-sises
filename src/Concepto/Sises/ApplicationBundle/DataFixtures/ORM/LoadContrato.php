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
use Concepto\Sises\ApplicationBundle\Entity\CoordinadorAsignacion;
use Concepto\Sises\ApplicationBundle\Entity\LugarEntrega;
use Concepto\Sises\ApplicationBundle\Entity\Persona;
use Concepto\Sises\ApplicationBundle\Entity\Personal\Coordinador;
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

        $ubicacion = $manager->getRepository('SisesApplicationBundle:Ubicacion\CentroPoblado')->findOneBy(array());

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
        $manager->persist($contrato);

        $contrato2 = new Contrato();
        $contrato2->setEmpresa($empresa);
        $contrato2->setContratante($contratante);
        $contrato2->setNombre("Contrato de mercados de {$empresa->getNombre()}");
        $contrato2->setDescripcion($contrato2->getNombre());
        $contrato2->setResolucion("Resolucion 00" . uniqid() . " de 2014");
        $contrato2->setFechaInicio(new \DateTime());
        $contrato2->setFechaCierre((new \DateTime())->add(new \DateInterval('P70D')));
        $contrato2->setValor(1500000);
        $manager->persist($contrato2);

        $servicio = new ServicioContratado();
        $servicio->setNombre("Almuerzos");
        $servicio->setDiasContratados(100);
        $servicio->setUnidadesDiarias(1500);
        $servicio->setValorUnitario(2560);
        $servicio->setCostoUnitario(1850);

        $contrato->addServicio($servicio);



        // Crea el coordinador
        $persona = new Persona();
        $persona->setNombre("Juancho");
        $persona->setApellidos("Pedrozo");
        $persona->setDocumento(uniqid());
        $manager->persist($persona);

        $coordinador = new Coordinador();
        $coordinador->setPersona($persona);
        $coordinador->setContrato($contrato);
        $manager->persist($coordinador);

        // Lugar entrega
        $lugar = new LugarEntrega();
        $lugar->setNombre("Las casitas");
        $lugar->setUbicacion($ubicacion);
        $manager->persist($lugar);

        // Asignacion
        $asignacion = new CoordinadorAsignacion();
        $asignacion->setCoordinador($coordinador);
        $asignacion->setLugar($lugar);
        $asignacion->setServicio($servicio);
        $manager->persist($asignacion);

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
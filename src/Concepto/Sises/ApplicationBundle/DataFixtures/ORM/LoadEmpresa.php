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


use Concepto\Sises\ApplicationBundle\Entity\Cargo;
use Concepto\Sises\ApplicationBundle\Entity\Empresa;
use Concepto\Sises\ApplicationBundle\Entity\Persona;
use Concepto\Sises\ApplicationBundle\Entity\PersonaCargo;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadEmpresa implements FixtureInterface, OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $empresa = new Empresa();
        $empresa->setNombre("DINCO");
        $empresa->setNit("800900123-4");
        $empresa->setPrivada(true);

        $cargo = new Cargo();
        $cargo->setNombre("Director");

        $persona = new Persona();
        $persona->setNombre("Juancho");
        $persona->setApellidos("Perez");
        $persona->setDocumento("112233223");

        $encargado = new PersonaCargo();
        $encargado->setPersona($persona);
        $encargado->setCargo($cargo);

        $empresa->setEncargado($encargado);

        $contratante = new Empresa();
        $contratante->setNombre('Gobernacion del Cesar');
        $contratante->setNit('800123899-12');

        $manager->persist($empresa);
        $manager->persist($contratante);
        $manager->persist($cargo);
        $manager->persist($persona);
        $manager->persist($encargado);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 100;
    }
}
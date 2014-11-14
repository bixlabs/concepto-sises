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


use Concepto\Sises\ApplicationBundle\Entity\Beneficiario;
use Concepto\Sises\ApplicationBundle\Entity\Beneficio;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;

class LoadBeneficiarios extends ContainerAwareFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //$this->container->get('faker.populator')->execute();
        //$this->createBeneficiio($manager);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @return array
     */
    function createBeneficiarios($manager)
    {
        $contrato = $manager->getRepository('SisesApplicationBundle:Contrato')->findOneBy(array());
        $personas = $manager->getRepository('SisesApplicationBundle:Persona')->findAll();
        $beneficiarios = array();

        foreach ($personas as $persona) {
            $beneficiario = new Beneficiario();
            $beneficiario->setContrato($contrato);
            $beneficiario->setPersona($persona);
            $manager->persist($beneficiario);
            $beneficiarios[] = $beneficiario;
        }

        return $beneficiarios;
    }

    /**
     * @param ObjectManager $manager
     */
    function createBeneficiio($manager)
    {
        $beneficiarios = $this->createBeneficiarios($manager);
        $lugares = $manager->getRepository('SisesApplicationBundle:LugarEntrega')->findAll();
        $servicios = $manager->getRepository('SisesApplicationBundle:ServicioContratado')->findAll();

        foreach ($lugares as $lugar) {
            foreach($servicios as $servicio) {
                foreach($beneficiarios as $beneficiario) {
                    $beneficio = new Beneficio();
                    $beneficio->setLugar($lugar);
                    $beneficio->setServicio($servicio);
                    $beneficio->setBeneficiario($beneficiario);
                    $manager->persist($beneficio);
                }
            }
        }
    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 310;
    }
}
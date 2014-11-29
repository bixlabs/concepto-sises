<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 22/10/14
 * Time: 12:35 PM
 */

namespace Concepto\Sises\ApplicationBundle\DataFixtures\ORM;


use Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadEntrega implements FixtureInterface, OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $contrato = $manager->getRepository('SisesApplicationBundle:Contrato')->findOneBy(array());

        $start = clone $contrato->getFechaInicio();
        $end = clone $contrato->getFechaInicio();
        $end->add(new \DateInterval('P10D'));

        $entrega = new Entrega();
        $entrega->setContrato($contrato);
        $entrega->setFechaInicio($start);
        $entrega->setFechaCierre($end);
        $entrega->setDiasGracia(5);
        $manager->persist($entrega);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 300;
    }
}
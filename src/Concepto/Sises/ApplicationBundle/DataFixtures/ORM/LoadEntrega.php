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
        $startDate = '2014-04-10';
        $date = new \DateTime($startDate);
        $dateEnd = (new \DateTime($startDate))->add(new \DateInterval('P30D'));

        $contrato = $manager->getRepository('SisesApplicationBundle:Contrato')->findOneBy(array());
        $entrega = new Entrega();
        $entrega->setContrato($contrato);
        $entrega->setFechaInicio($date);
        $entrega->setFechaCierre($dateEnd);
        $entrega->setDiasGracia(5);
        $manager->persist($entrega);

        $date2 = (new \DateTime($startDate))->add(new \DateInterval('P31D'));
        $dateEnd2 = (new \DateTime($startDate))->add(new \DateInterval('P61D'));

        $entrega2 = new Entrega();
        $entrega2->setContrato($contrato);
        $entrega2->setFechaInicio($date2);
        $entrega2->setFechaCierre($dateEnd2);
        $entrega2->setDiasGracia(5);
        $manager->persist($entrega2);

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
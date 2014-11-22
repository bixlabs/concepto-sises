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


use Concepto\Sises\ApplicationBundle\Entity\Seguridad\Usuario;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Util\UserManipulator;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;

class LoadUsuarios extends ContainerAwareFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $coordinador =
            $manager->getRepository('SisesApplicationBundle:Personal\Coordinador')->findOneBy(array());

        $director =
            $manager->getRepository('SisesApplicationBundle:Personal\Director')->findOneBy(array());

        /** @var UserManipulator $manipulator */
        $manipulator = $this->container->get('concepto.sises.user_manipulator');

        //$manipulator->deteleAll();

        $manipulator->create(
            'admin',
            'admin',
            'admin@sises.com',true, false,
            null,
            Usuario::ADMIN
        );
        $manipulator->create(
            'coordinador',
            'coordinador',
            'coordinador@sises.com', true, false,
            $coordinador->getId(),
            Usuario::COORDINADOR
        );
        $manipulator->create(
            'director',
            'director',
            'director@sises.com', true, false,
            $director->getId(),
            Usuario::DIRECTOR
        );

        $manipulator->addRole('admin', 'ROLE_ADMIN');
        $manipulator->addRole('coordinador', 'ROLE_COORDINADOR');
        $manipulator->addRole('director', 'ROLE_DIRECTOR');
    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 9999;
    }
}
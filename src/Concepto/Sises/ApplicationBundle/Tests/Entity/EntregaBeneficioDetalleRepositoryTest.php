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

namespace Concepto\Sises\ApplicationBundle\Tests\Entity;


use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaBeneficioDetalleRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EntregaBeneficioDetalleRepositoryTest extends KernelTestCase
{
    /**
     * @var EntregaBeneficioDetalleRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $em;

    public function testCalcular()
    {
        $entrega = $this->em->getRepository('SisesApplicationBundle:Entrega\Entrega')->findOneBy(array());
        $result = $this->repository->calcular($entrega->getId());

        var_dump($result);
    }


    protected function setUp()
    {
        self::bootKernel(array('environment' => 'dev'));

        //shell_exec(__DIR__ . '/../../Resources/test/reload_database.sh');

        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->repository = $this->em->getRepository('SisesApplicationBundle:Entrega\EntregaBeneficioDetalle');
    }
} 
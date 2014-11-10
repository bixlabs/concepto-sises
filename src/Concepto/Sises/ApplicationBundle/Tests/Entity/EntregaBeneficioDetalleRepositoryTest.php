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
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\ArrayInput;

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

    /**
     * @var string
     */
    private $entrega = "ad8d9dd9-65a5-11e4-bff3-1867b083cd22";

    public function testCalcular()
    {
        $servicios = $this->em->getRepository('SisesApplicationBundle:ServicioContratado')->findAll();

        $results = $this->repository->calcularv2($this->entrega);

        $this->equalTo(count($servicios), count($results));
    }

    protected function setUp()
    {
        self::bootKernel();

        shell_exec(__DIR__ . '/../../Resources/test/reload_database.sh');

        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $this->repository = $this->em->getRepository('SisesApplicationBundle:Entrega\EntregaBeneficioDetalle');
    }
} 
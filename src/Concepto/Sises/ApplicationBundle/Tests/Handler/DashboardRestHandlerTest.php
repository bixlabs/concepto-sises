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

namespace Concepto\Sises\ApplicationBundle\Tests\Handler;


use Concepto\Sises\ApplicationBundle\Handler\DashboardRestHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DashboardRestHandlerTest extends KernelTestCase
{
    /**
     * @var DashboardRestHandler
     */
    private $handler;

    protected function setUp()
    {
        self::bootKernel(array('environment' => 'dev'));

        $this->handler = static::$kernel->getContainer()->get('conceptos_sises_dashboard');
    }

    public function testcalcule()
    {
        $results = $this->handler->calcule();

        var_dump($results);
    }
} 
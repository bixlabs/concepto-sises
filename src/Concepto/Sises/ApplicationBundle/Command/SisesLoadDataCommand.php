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

namespace Concepto\Sises\ApplicationBundle\Command;


use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaAsignacion;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaBeneficioDetalle;
use Concepto\Sises\ApplicationBundle\Handler\Entrega\EntregaAsignacionRestHandler;
use Doctrine\ORM\EntityManager;
use Goodby\CSV\Import\Standard as CSV;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SisesLoadDataCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $manager;

    protected function configure()
    {
        $this->setName('sises:load:data')
            ->setDescription("Carga datos de prueba")
            ->addArgument(
                'file',
                InputArgument::OPTIONAL,
                "Ruta del archivo de pruebas",
                __DIR__ . '/../Resources/files/primera_entrega.csv'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');

        $config = new CSV\LexerConfig();
        $config
            ->setDelimiter(',')
            ->setEnclosure('"')
            ->setFromCharset('UTF-8')
            ->setToCharset('UTF-8')
        ;

        $this->manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $lexer = new CSV\Lexer($config);
        $interpreter = new CSV\Interpreter();
        $interpreter->addObserver($this->getObserver());

        $lexer->parse($file, $interpreter);
        $this->manager->flush();

    }

    public function getObserver()
    {
        /** @var EntregaAsignacionRestHandler $handler */
        $handler = $this->getContainer()->get('concepto_sises_entrega_asignacion.handler');
        $asignaciones = $handler->cget();
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        return function(array $columns) use ($asignaciones, $handler, $manager) {

            // la primera columna se omite
            if ($columns[0] == "Dia") {
                return;
            }

            /** @var EntregaAsignacion $asignacion */
            foreach ($asignaciones as $asignacion) {
                $lugar = $asignacion->getAsignacion()->getLugar()->getNombre();
                $servicio = $asignacion->getAsignacion()->getServicio()->getNombre();
                // Se asegura de sumar los dias apropiadamente,  1 = primer dia de la entrega


                if ($lugar == $columns[2] && $servicio == $columns[1]) {
                    $dia = ((int) $columns[0]) -1;
                    $count = (int) $columns[3];

                    $fecha = clone $asignacion->getEntrega()->getFechaInicio();
                    $results = $handler->getOrCreateEntregaBeneficio(array(
                        'id' => $asignacion->getId(),
                        'fecha' => $fecha->add( new \DateInterval("P{$dia}D"))->format('Y-m-d\TH:i:sO')
                    ))->getData();

                    for ($i = 0; $i < $count; $i++) {
                        /** @var EntregaBeneficioDetalle $detalle */
                        $detalle = $results[$i];
                        $detalle->setEstado(true);
                        $this->manager->persist($detalle);
                    }
                }
            }
        };
    }
} 
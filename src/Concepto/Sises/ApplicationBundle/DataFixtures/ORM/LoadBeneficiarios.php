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
use Concepto\Sises\ApplicationBundle\Entity\LugarEntrega;
use Concepto\Sises\ApplicationBundle\Entity\Persona;
use Concepto\Sises\ApplicationBundle\Entity\ServicioContratado;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;

class LoadBeneficiarios extends ContainerAwareFixture implements OrderedFixtureInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    const COL_CODIGO_UBICACION = 2;
    const COL_DOCUMENTO = 21;
    const COL_1_NOM = 25;
    const COL_2_NOM = 26;
    const COL_1_APE = 23;
    const COL_2_APE = 24;
    const COL_NOM_LUGAR = 8;

    const BAG_UBICACION = 'ubicacion';
    const BAG_LUGAR = 'lugar';
    const BAG_PERSONA = 'persona';

    private $bag;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $servicios = $manager->getRepository('SisesApplicationBundle:ServicioContratado')->findAll();

        $this->bag = array(
            self::BAG_UBICACION => array(),
            self::BAG_LUGAR => array(),
            self::BAG_PERSONA => array()
        );

        foreach ($servicios as $servicio) {
            $this->loadBeneficio($servicio);
        }

        $manager->flush();
    }

    public function loadBeneficio(ServicioContratado $servicio)
    {
        $file = sprintf(
            __DIR__ . '/../../Resources/files/%s.csv',
            $servicio->getNombre() == "Desayunos" ? '100' : '200'
        );

        $config = new LexerConfig();
        $config
            ->setDelimiter(',')
            ->setEnclosure('"')
            ->setFromCharset('UTF-8')
            ->setToCharset('UTF-8')
        ;

        $lexer = new Lexer($config);

        $manager = $this->manager;
        $bag = &$this->bag;

        $repo = $this->manager->getRepository('SisesApplicationBundle:Ubicacion\CentroPoblado');
        $findLugar = function($columns) use ($repo, &$bag, $manager) {
            $nombre = $columns[LoadBeneficiarios::COL_NOM_LUGAR];
            $codigoLugar = $columns[LoadBeneficiarios::COL_CODIGO_UBICACION];

            if (!isset($bag[LoadBeneficiarios::BAG_UBICACION][$nombre])) {

                if (!isset($bag[LoadBeneficiarios::BAG_LUGAR][$codigoLugar])) {
                    $bag[LoadBeneficiarios::BAG_LUGAR][$codigoLugar] = $repo->findOneBy(array('codigoDane' => "{$codigoLugar}000"));
                }

                $lugar = new LugarEntrega();
                $lugar->setUbicacion($bag[LoadBeneficiarios::BAG_LUGAR][$codigoLugar]);
                $lugar->setNombre($nombre);
                $manager->persist($lugar);

                $bag[LoadBeneficiarios::BAG_UBICACION][$nombre] = $lugar;
            }

            return $bag[LoadBeneficiarios::BAG_UBICACION][$nombre];
        };

        $repo2 = $this->manager->getRepository('SisesApplicationBundle:Persona');
        $findPersona = function($column) use ($repo2, &$bag, $manager) {
            $documento = $column[LoadBeneficiarios::COL_DOCUMENTO];

            // Busca la persona en la base d datos
            if (!isset($bag[LoadBeneficiarios::BAG_PERSONA][$documento])) {
                $bag[LoadBeneficiarios::BAG_PERSONA][$documento] = $repo2->findOneBy(array('documento' => $documento));
            }

            // Crea la persona
            if (!isset($bag[LoadBeneficiarios::BAG_PERSONA][$documento])) {
                $persona = new Persona();
                $persona->setNombre(trim(
                    "{$column[LoadBeneficiarios::COL_1_NOM]} {$column[LoadBeneficiarios::COL_2_NOM]}"
                ));
                $persona->setApellidos(trim(
                    "{$column[LoadBeneficiarios::COL_1_APE]} {$column[LoadBeneficiarios::COL_2_APE]}"
                ));
                $persona->setDocumento($documento);
                $manager->persist($persona);

                $bag[LoadBeneficiarios::BAG_PERSONA][$documento] = $persona;
            }

            return $bag[LoadBeneficiarios::BAG_PERSONA][$documento];
        };

        $interpreter = new Interpreter();
        $interpreter->addObserver(function (array $columns) use ($bag, $findLugar, $findPersona, $servicio, $manager) {
            if ($columns[0] == "AÑO") {
                return;
            }

            $beneficiario = new Beneficiario();
            $beneficiario->setPersona($findPersona($columns));
            $beneficiario->setContrato($servicio->getContrato());

            $beneficio = new Beneficio();
            $beneficio->setBeneficiario($beneficiario);
            $beneficio->setLugar($findLugar($columns));
            $beneficio->setServicio($servicio);

            $manager->persist($beneficiario);
            $manager->persist($beneficio);
        });

        $lexer->parse($file, $interpreter);

        ksort($bag['ubicacion']);

        $manager->flush();
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
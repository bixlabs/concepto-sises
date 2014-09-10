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


use Concepto\Sises\ApplicationBundle\Entity\Ubicacion\CentroPoblado;
use Concepto\Sises\ApplicationBundle\Entity\Ubicacion\Departamento;
use Concepto\Sises\ApplicationBundle\Entity\Ubicacion\Municipio;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Debug\Exception\ContextErrorException;

class LoadUbicacion implements FixtureInterface
{
    const DELIMITER = ',';
    const ENCLOSURE = '"';
    const LENGTH = 4096;

    const D_COD = 0;
    const D_NOM = 3;
    const M_COD = 1;
    const M_NOM = 4;
    const C_COD = 2;
    const C_NOM = 5;
    const C_TYP = 6;

    private $added = array();

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     *
     * @throws \Exception
     */
    function load(ObjectManager $manager)
    {
        // Division politica de colombia
        // http://www.dane.gov.co/Divipola/archivos/Listado_2014.xls
        $file = __DIR__ . '/../../Resources/files/Listado_2014.csv';

        if (!file_exists($file)) {
            throw new \Exception("El archivo '{$file}' no existe");
        }

        $handler = fopen($file, 'r');

        // Se omite el encabezado
        fgetcsv($handler, self::LENGTH, self::DELIMITER, self::ENCLOSURE);

        while (false !== ($row = fgetcsv($handler, self::LENGTH, self::DELIMITER, self::ENCLOSURE)))
        {
            $this->process($row);
        }

        fclose($handler);

        foreach($this->added as $toAdd) {
            $manager->persist($toAdd);
        }
        $manager->flush();
    }

    private function process($row)
    {
        // Departamento
        $key_d = "{d}{$row[self::D_COD]}";
        if (!isset($this->added[$key_d])) {
            $d = new Departamento();
            $d->setCodigoDane($row[self::D_COD]);
            $d->setNombre($this->clean($row[self::D_NOM]));
            $this->added[$key_d] = $d;
        }

        $key_m = "{m}{$row[self::M_COD]}";
        //Municipio
        if (!isset($this->added[$key_m])) {
            $m = new Municipio();
            $m->setCodigoDane($row[self::M_COD]);
            $m->setNombre($this->clean($row[self::M_NOM]));
            $m->setDepartamento($this->added[$key_d]);
            $this->added[$key_m] = $m;
        }

        //Centro poblado
        $key_c = "{c}{$row[self::C_COD]}";
        if (!isset($this->added[$key_c])) {
            $c = new CentroPoblado();
            $c->setCodigoDane($row[self::C_COD]);
            $c->setNombre($this->clean($row[self::C_NOM]));
            $c->setTipoDane($row[self::C_TYP]);
            $c->setMunicipio($this->added[$key_m]);
            $this->added[$key_c] = $c;
        }
    }

    private function clean($value)
    {
        $value = mb_strtolower($value, 'UTF-8');
        $value[0] = mb_strtoupper($value[0], 'UTF-8');

        return $value;
    }
}
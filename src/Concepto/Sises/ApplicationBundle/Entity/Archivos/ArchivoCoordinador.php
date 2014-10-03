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

namespace Concepto\Sises\ApplicationBundle\Entity\Archivos;


use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Class ArchivoCoordinador
 * @package Concepto\Sises\ApplicationBundle\Entity\Archivos
 * @Entity()
 * @Table(name="recurso_humano_coordinador_archivo")
 */
class ArchivoCoordinador extends Archivo
{
    /**
     * @var Documentable
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Personal\Coordinador", inversedBy="archivos")
     */
    protected $documentable;
}
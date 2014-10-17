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

namespace Concepto\Sises\ApplicationBundle\Entity\Seguridad;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use FOS\UserBundle\Entity\User;

/**
 * Class Usuario
 * @package Concepto\Sises\ApplicationBundle\Entity\Seguridad
 * @Entity()
 * @Table(name="seguridad_usuario")
 */
class Usuario extends User
{
    /**
     * @Id
     * @Column(name="id", length=36)
     * @GeneratedValue(strategy="UUID")
     */
    protected $id;
}
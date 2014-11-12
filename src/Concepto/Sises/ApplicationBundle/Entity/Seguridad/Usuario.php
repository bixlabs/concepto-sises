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
    const ADMIN = 'administrador';
    const COORDINADOR = 'coordinador';
    const DIRECTOR = 'director';

    /**
     * @Id
     * @Column(name="id", length=36)
     * @GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var string
     * @Column(name="tipo", length=100)
     */
    protected $tipo;

    /**
     * @var string
     * @Column(name="related", length=36)
     */
    protected $related;

    public function __construct()
    {
        parent::__construct();

        $this->tipo = self::COORDINADOR;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return string
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * @param string $related
     */
    public function setRelated($related)
    {
        $this->related = $related;
    }
}
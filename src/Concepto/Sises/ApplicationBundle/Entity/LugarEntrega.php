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

namespace Concepto\Sises\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class LugarEntrega
 * @package Concepto\Sises\ApplicationBundle\Entity
 * @Entity()
 * @Table(
 *      name="lugar_entrega",
 *      uniqueConstraints={
 *          @UniqueConstraint(name="lugar_entrega", columns={"ubicacion_id", "nombre"})
 *      }
 * )
 * @UniqueEntity(
 *      message="No pueden existir dos lugares con el mismo nombre en un mismo lugar",
 *      fields={"ubicacion", "nombre"}
 * )
 */
class LugarEntrega {
    /**
     * @var string
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", length=36)
     */
    protected $id;

    /**
     * @var string
     * @Column(name="nombre", length=250)
     * @NotNull()
     */
    protected $nombre;

    /**
     * @var Ubicacion\CentroPoblado
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Ubicacion\CentroPoblado")
     * @NotNull()
     */
    protected $ubicacion;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return \Concepto\Sises\ApplicationBundle\Entity\Ubicacion\CentroPoblado
     */
    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Ubicacion\CentroPoblado $ubicacion
     */
    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;
    }
}
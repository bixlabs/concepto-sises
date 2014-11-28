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

namespace Concepto\Sises\ApplicationBundle\Entity\Entrega;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Observacion
 * @package Concepto\Sises\ApplicationBundle\Entity\Entrega
 * @ORM\Entity()
 * @ORM\Table(name="observacion")
 */
class Observacion {
    /**
     * @var string
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(name="id", length=36)
     */
    protected $id;

    /**
     *
     * @var string
     * @ORM\Column(name="related", length=36)
     */
    protected $related;

    /**
     * @var \DateTime
     * @ORM\Column(name="create_at", type="datetime")
     */
    protected $createAt;

    /**
     * @var string
     * @ORM\Column(name="mensaje", type="text")
     */
    protected $contenido;

    /**
     * @var string
     * @ORM\Column(name="last_state", type="text", nullable=true)
     */
    protected $lastState;

    /**
     * Se inicializa el momento
     */
    function __construct()
    {
        $this->createAt = new \DateTime();
    }

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
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * @param string $contenido
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
    }

    /**
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * @param \DateTime $createAt
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;
    }

    /**
     * @return string
     */
    public function getLastState()
    {
        return $this->lastState;
    }

    /**
     * @param string $lastState
     */
    public function setLastState($lastState)
    {
        $this->lastState = $lastState;
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
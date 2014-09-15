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
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class PersonaCargo
 * @package Concepto\Sises\ApplicationBundle\Entity
 * @Entity()
 * @Table(name="persona_a_cargo")
 */
class PersonaCargo
{
    /**
     * @var string
     * @Id()
     * @GeneratedValue(strategy="UUID")
     * @Column(name="id", length=36)
     */
    protected $id;

    /**
     * @var Cargo
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Cargo")
     * @NotNull()
     */
    protected $cargo;

    /**
     * @var Persona
     * @ManyToOne(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Persona")
     * @NotNull()
     */
    protected $persona;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Cargo $cargo
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
    }

    /**
     * @return \Concepto\Sises\ApplicationBundle\Entity\Cargo
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    /**
     * @param \Concepto\Sises\ApplicationBundle\Entity\Persona $persona
     */
    public function setPersona($persona)
    {
        $this->persona = $persona;
    }

    /**
     * @return \Concepto\Sises\ApplicationBundle\Entity\Persona
     */
    public function getPersona()
    {
        return $this->persona;
    }
}
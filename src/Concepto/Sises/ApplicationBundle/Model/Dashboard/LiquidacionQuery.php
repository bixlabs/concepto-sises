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

namespace Concepto\Sises\ApplicationBundle\Model\Dashboard;


class LiquidacionQuery
{
    /**
     * @var string
     */
    protected $empresa;

    /**
     * @var string
     */
    protected $lugar;

    /**
     * @var string
     */
    protected $recurso;

    /**
     * @var \DateTime
     */
    protected $start;

    /**
     * @var \DateTime
     */
    protected $end;

    function __construct()
    {
        $end = new \DateTime();
        $start = clone $end;
        $start->sub(new \DateInterval('P1M'));

        $this->end = $end;
        $this->start = $start;
    }

    /**
     * @return string
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param string $empresa
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return string
     */
    public function getLugar()
    {
        return $this->lugar;
    }

    /**
     * @param string $lugar
     */
    public function setLugar($lugar)
    {
        $this->lugar = $lugar;
    }

    /**
     * @return string
     */
    public function getRecurso()
    {
        return $this->recurso;
    }

    /**
     * @param string $recurso
     */
    public function setRecurso($recurso)
    {
        $this->recurso = $recurso;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }
} 
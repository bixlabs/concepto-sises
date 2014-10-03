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

namespace Concepto\Sises\ApplicationBundle\Entity\Personal;


use Concepto\Sises\ApplicationBundle\Entity\Empresa;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * Class Director
 * @package Concepto\Sises\ApplicationBundle\Entity\Personal
 * @Entity()
 * @Table(name="recurso_humano_director")
 */
class Director extends AbstractPersonal
{
    /**
     * @{inheridoc}
     * @OneToMany(
     *  targetEntity="Concepto\Sises\ApplicationBundle\Entity\Archivos\ArchivoDirector",
     *  mappedBy="documentable",
     *  cascade={"persist"}
     * )
     */
    protected $archivos;

    /**
     * @var Collection
     * @OneToMany(targetEntity="Concepto\Sises\ApplicationBundle\Entity\Empresa", mappedBy="director")
     * @JoinTable(name="director_empresa")
     * @Groups({"details", "list"})
     * @MaxDepth(depth=2)
     */
    protected $empresas;

    function __construct()
    {
        parent::__construct();
        $this->empresas = new ArrayCollection();
    }

    /**
     * @return Collection
     */
    public function getEmpresas()
    {
        return $this->empresas;
    }

    /**
     * @param Collection $empresas
     */
    public function setEmpresas($empresas)
    {
        $this->empresas = $empresas;
    }

    /**
     * @param Empresa $empresa
     */
    public function addEmpresa($empresa)
    {
        if (!$this->empresas->contains($empresa)) {
            $empresa->setDirector($this);
            $this->empresas->add($empresa);
        }
    }

    /**
     * @param Empresa $empresa
     */
    public function removeEmpresa($empresa) {
        if ($this->empresas->contains($empresa)) {
            $empresa->setDirector(null);
            $this->empresas->removeElement($empresa);
        }
    }
}
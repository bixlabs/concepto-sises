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

namespace Concepto\Sises\ApplicationBundle\Features\Context;


use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Concepto\Sises\ApplicationBundle\Entity\Archivo;
use Concepto\Sises\ApplicationBundle\Entity\Empresa;
use Concepto\Sises\ApplicationBundle\Entity\Persona;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\KernelInterface;

class DomainContext implements SnippetAcceptingContext
{
    use KernelDictionary;

    private function removeAll($name)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()
            ->get('doctrine.orm.default_entity_manager');
        $repository = $em
            ->getRepository('SisesApplicationBundle:' . $name);

        foreach ($repository->findAll() as $contrato) {
            $em->remove($contrato);
        }

        $em->flush();
    }

    /**
     * @Given que no hay contratos
     */
    public function queNoHayContratos()
    {
        $this->removeAll('Contrato');
    }

    /**
     * @Given que no hay empresas
     */
    public function queNoHayEmpresas()
    {
        $this->removeAll('Empresa');
    }

    /**
     * @Given que no hay personas
     */
    public function queNoHayPersonas()
    {
        $this->removeAll('Persona');
    }
}
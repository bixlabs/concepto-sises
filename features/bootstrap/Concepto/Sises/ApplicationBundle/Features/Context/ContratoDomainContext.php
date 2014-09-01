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
use Doctrine\ORM\EntityManager;

class ContratoDomainContext implements SnippetAcceptingContext
{
    use KernelDictionary;

    /**
     * @Given que no hay contratos
     */
    public function queNoHayContratos()
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()
            ->get('doctrine.orm.default_entity_manager');
        $repository = $em
            ->getRepository('SisesApplicationBundle:Contrato');

        foreach ($repository->findAll() as $contrato) {
            $em->remove($contrato);
        }

        $em->flush();
    }

}
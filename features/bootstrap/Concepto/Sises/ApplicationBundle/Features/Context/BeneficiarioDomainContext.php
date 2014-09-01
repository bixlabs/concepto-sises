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

class BeneficiarioDomainContext implements SnippetAcceptingContext
{
    use KernelDictionary;

    /**
     * @Given que no hay beneficiarios
     */
    public function queNoHayBeneficiarios()
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()
            ->get('doctrine.orm.default_entity_manager');
        $repository = $em
            ->getRepository('SisesApplicationBundle:Beneficiario');

        foreach ($repository->findAll() as $beneficiario) {
            $em->remove($beneficiario);
        }

        $em->flush();
    }

}
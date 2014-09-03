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

/**
 * Class PersonaDomainContext
 * @package Concepto\Sises\ApplicationBundle\Features\Context
 */
class PersonaDomainContext implements SnippetAcceptingContext
{
    use KernelDictionary;

    /**
     * @Given que no hay personas
     */
    public function queNoHayPersonas()
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()
            ->get('doctrine.orm.default_entity_manager');
        $repository = $em
            ->getRepository('SisesApplicationBundle:Persona');

        foreach ($repository->findAll() as $persona) {
            $em->remove($persona);
        }

        $em->flush();
    }

}
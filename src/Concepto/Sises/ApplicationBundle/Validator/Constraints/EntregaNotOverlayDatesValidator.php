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

namespace Concepto\Sises\ApplicationBundle\Validator\Constraints;


use Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class EntregaNotOverlayDatesValidator
 * @package Concepto\Sises\ApplicationBundle\Validator\Constraints
 * @Service(id="concepto.entrega.validator")
 * @Tag(name="validator.constraint_validator", attributes={"alias"="entrega_not_overlay"})
 */
class EntregaNotOverlayDatesValidator extends ConstraintValidator
{
    /**
     * @var ExecutionContextInterface
     */
    protected $context;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @param EntityManager $manager
     * @InjectParams({
     *  "manager" = @Inject("doctrine.orm.entity_manager")
     * })
     */
    function __construct($manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var EntregaNotOverlayDates $constraint */
        /** @var Entrega $value */

        $class = ClassUtils::getClass($this->context->getObject());
        $qb = $this->manager->getRepository($class)->createQueryBuilder('a');

        $qb
            ->andWhere('a.contrato = :contrato')
            ->andWhere(':inico < a.fechaCierre')
            ->andWhere(':cierre > a.fechaInicio')
            ->setParameter('contrato', $value->getContrato())
            ->setParameter('inico', $value->getFechaInicio())
            ->setParameter('cierre', $value->getFechaCierre());

        if ($value->getId()) {
            $qb->andWhere('a.id != :id')
                ->setParameter('id', $value->getId());
        }

        $results = $qb->getQuery()->execute();

        if (count($results) > 0) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
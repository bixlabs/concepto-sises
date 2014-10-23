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


use Concepto\Sises\ApplicationBundle\Entity\EntityRepository;
use Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega;
use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class EntregaNotOverlayDatesValidator
 * @package Concepto\Sises\ApplicationBundle\Validator\Constraints
 * @Service(id="concepto.entrega.validator")
 * @Tag(name="validator.constraint_validator", attributes={"alias"="entrega_not_overlay"})
 */
class EntregaNotOverlayDatesValidator extends ConstraintValidator
{

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @param ObjectManager $manager
     * @InjectParams({
     *  "manager" = @Inject("doctrine.orm.entity_manager")
     * })
     */
    function __construct($manager)
    {
        $this->repository = $manager->getRepository('SisesApplicationBundle:Entrega\Entrega');
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $properties = array(
            'inicio' => 'fechaInicio',
            'cierre' => 'fechaCierre'
        );
        $accesor = PropertyAccess::createPropertyAccessor();

        foreach ($properties as $key => $property) {

            /** @var EntregaNotOverlayDates $constraint */
            /** @var Entrega $value */
            $qb =
                $this->repository->createQueryBuilder('a')
                    ->where(":{$key} BETWEEN a.fechaInicio AND a.fechaCierre")
                    ->setParameter($key, $accesor->getValue($value, $property))
            ;

            if ($value->getId()) {
                $qb->andWhere('a.id != :id')
                    ->setParameter('id', $value->getId());
            }

            $results = $qb->getQuery()->execute();

            if (count($results) > 0) {
                $this->context->buildViolation($constraint->message)
                    ->atPath($property)
                    ->addViolation();
            }
        }
    }
}
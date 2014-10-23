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

namespace Concepto\Sises\ApplicationBundle\Form;


use Concepto\Sises\ApplicationBundle\Handler\RestHandlerInterface;
use JMS\DiExtraBundle\Annotation\FormType;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class LugarEntregaType
 * @package Concepto\Sises\ApplicationBundle\Form
 * @FormType(alias="lugar")
 */
class LugarEntregaType extends AbstractType
{
    /**
     * @var RestHandlerInterface
     */
    private $provider;

    /**
     * @param $provider
     * @InjectParams({
     *   "provider" = @Inject("concepto_sises_ubicacion.handler")
     * })
     */
    function __construct($provider)
    {
        $this->provider = $provider;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('ubicacion', null, array(
                'property' => 'id',
                'choices' => array()
            ))
        ;

        $pr = $this->provider;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) use ($pr, $builder) {
            $form = $event->getForm();
            $data = $event->getData();

            if ($form->has('ubicacion')) {
                $form->remove('ubicacion');

                $ubicacion = $pr->get($data['ubicacion']);

                $formFactory = $builder->getFormFactory();
                $form->add($formFactory->createNamed('ubicacion', 'entity', null, array(
                    'auto_initialize' => false,
                    'property' => 'id',
                    'class' => 'Concepto\Sises\ApplicationBundle\Entity\Ubicacion\CentroPoblado',
                    'choices' => $ubicacion ? array($ubicacion) : array()
                )));
            }
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Concepto\Sises\ApplicationBundle\Entity\LugarEntrega'
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'lugar';
    }
}
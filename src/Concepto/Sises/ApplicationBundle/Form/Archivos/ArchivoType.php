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

namespace Concepto\Sises\ApplicationBundle\Form\Archivos;


use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation\FormType;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ArchivoType
 * @package Concepto\Sises\ApplicationBundle\Form\Archivos
 * @FormType(alias="archivo")
 */
class ArchivoType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $provider;

    /**
     * @param $provider
     * @InjectParams({
     *   "provider" = @Inject("doctrine.orm.default_entity_manager")
     * })
     */
    function __construct($provider)
    {
        $this->provider = $provider;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $provider = $this->provider;
        $builder->add('id', 'choice', array('choices' => array()));
        $builder->addEventListener(FormEvents::PRE_SUBMIT,
                function(FormEvent $event) use ($builder, $provider) {

                $data = $event->getData();
                $form = $event->getForm();

                $archivo = $provider->find(
                    'SisesApplicationBundle:Archivos\ArchivoDocumentable',
                    $data['id']
                );

                if ($form->has('id')) {
                    $form->remove('id');

                    $id = $builder->getFormFactory()->createNamed('id', 'choice', null, array(
                        'auto_initialize' => false,
                        'choices' => $archivo ? array($data['id']) : array()
                    ));

                    $form->add($id);
                }

            });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => true
        ));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'archivo';
    }
}
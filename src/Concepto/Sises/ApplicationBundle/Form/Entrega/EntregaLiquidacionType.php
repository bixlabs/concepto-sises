<?php

namespace Concepto\Sises\ApplicationBundle\Form\Entrega;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntregaLiquidacionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaInicio', 'datetime', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
            ))
            ->add('fechaCierre', 'datetime', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
            ))
            ->add('diasGracia', null, array(
                'label' => 'Dias de gracia'
            ))
            ->add('contrato', 'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\Contrato'
            ))
            ->add('estado', null, array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaLiquidacion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'concepto_sises_applicationbundle_entrega_entregaliquidacion';
    }
}

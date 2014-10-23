<?php

namespace Concepto\Sises\ApplicationBundle\Form\Entrega;

use Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntregaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('fechaInicio', 'date', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
            ))
            ->add('fechaCierre', 'date', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
            ))
            ->add('diasGracia', null, array(
                'label' => 'Dias de gracia'
            ))
            ->add('estado', null, array(
                'empty_data' => Entrega::OPEN
            ))
            ->add('contrato', 'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\Contrato'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Concepto\Sises\ApplicationBundle\Entity\Entrega\Entrega'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'concepto_sises_applicationbundle_entrega_entrega';
    }
}

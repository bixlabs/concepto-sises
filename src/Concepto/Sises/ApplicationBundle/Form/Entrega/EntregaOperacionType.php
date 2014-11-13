<?php

namespace Concepto\Sises\ApplicationBundle\Form\Entrega;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntregaOperacionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('cantidad')
            ->add('fechaEntrega', 'datetime', array(
                'input'  => 'datetime',
                'widget' => 'single_text',
            ))
            ->add('servicio', 'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\ServicioOperativo'
            ))
            ->add('liquidacion', 'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaLiquidacion'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaOperacion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'concepto_sises_applicationbundle_entrega_entregaoperacion';
    }
}

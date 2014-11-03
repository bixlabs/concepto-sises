<?php

namespace Concepto\Sises\ApplicationBundle\Form\Entrega;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntregaBeneficioDetalleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('estado')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Concepto\Sises\ApplicationBundle\Entity\Entrega\EntregaBeneficioDetalle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'concepto_sises_applicationbundle_entrega_entregabeneficiodetalle';
    }
}

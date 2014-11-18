<?php

namespace Concepto\Sises\ApplicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CoordinadorAsignacionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lugar', 'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\LugarEntrega'
            ))
            ->add('servicio', 'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\ServicioContratado'
            ))
            ->add('coordinador', 'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\Personal\Coordinador'
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Concepto\Sises\ApplicationBundle\Entity\CoordinadorAsignacion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'concepto_sises_applicationbundle_coordinadorasignacion';
    }
}

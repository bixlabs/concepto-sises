<?php

namespace Concepto\Sises\ApplicationBundle\Form\Personal;

use Concepto\Sises\ApplicationBundle\Form\CoordinadorAsignacionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CoordinadorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroCuenta')
            ->add('observacionesFinancieras')
            ->add('contrato', 'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\Contrato'
            ))
            ->add('persona', 'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\Persona'
            ))
            ->add('entidadFinanciera', 'entity_hidden', array(
                'class' => 'Concepto\Sises\ApplicationBundle\Entity\Financiera\Entidad'
            ))
            ->add('asignacion', 'collection', array(
                'type' => new CoordinadorAsignacionType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Concepto\Sises\ApplicationBundle\Entity\Personal\Coordinador'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'concepto_sises_applicationbundle_personal_coordinador';
    }
}

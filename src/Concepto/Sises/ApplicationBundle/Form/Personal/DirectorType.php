<?php

namespace Concepto\Sises\ApplicationBundle\Form\Personal;

use Concepto\Sises\ApplicationBundle\Form\EmpresaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DirectorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('numeroCuenta')
            ->add('observacionesFinancieras')
            ->add('empresas', 'collection', array(
                'type' => new EmpresaType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('persona', null, array('property' => 'id'))
            ->add('entidadFinanciera', null, array('property' => 'id'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Concepto\Sises\ApplicationBundle\Entity\Personal\Director'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'concepto_sises_applicationbundle_personal_director';
    }
}

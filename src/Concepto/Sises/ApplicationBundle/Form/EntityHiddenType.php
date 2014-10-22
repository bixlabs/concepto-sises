<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 22/10/14
 * Time: 05:31 PM
 */

namespace Concepto\Sises\ApplicationBundle\Form;


use Concepto\Sises\ApplicationBundle\Form\DataTransformer\EntityToUuid;
use Doctrine\Common\Persistence\ObjectManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class EntityHiddenType
 * @package Concepto\Sises\ApplicationBundle\Form
 * @Service("concepto.entity_hiden.type")
 * @Tag(name="form.type", attributes={"alias": "entity_hidden"})
 */
class EntityHiddenType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @param ObjectManager $manager
     * @InjectParams({
     *  "manager" = @Inject("doctrine.orm.entity_manager")
     * })
     */
    function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new EntityToUuid($this->manager, $options['class']));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('class'));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'entity_hidden';
    }

    public function getParent()
    {
        return 'hidden';
    }
}
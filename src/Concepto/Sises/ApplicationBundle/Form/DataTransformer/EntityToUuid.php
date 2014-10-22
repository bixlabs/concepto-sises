<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 22/10/14
 * Time: 05:27 PM
 */

namespace Concepto\Sises\ApplicationBundle\Form\DataTransformer;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToUuid implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    private $class;

    function __construct($manager, $class)
    {
        $this->class = $class;
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if (is_object($value) && method_exists($value, 'getId')) {
            return $value->getId();
        }

        throw new TransformationFailedException("object must be provided, given " . get_class($value));
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($id)
    {
        if ($id) {
            $entity = $this->manager->find($this->class, $id);

            if (is_null($entity)) {
                throw new TransformationFailedException();
            }

            return $entity;
        }

        return null;
    }
}
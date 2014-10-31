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

namespace Concepto\Sises\ApplicationBundle\Serializer\Exclusion;


use JMS\Serializer\Context;
use JMS\Serializer\Exclusion\ExclusionStrategyInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\PropertyMetadata;

class ListExclusionStrategy implements ExclusionStrategyInterface
{
    private $classes = array();

    function __construct($classes = array())
    {
        $this->classes = $classes;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldSkipClass(ClassMetadata $metadata, Context $context)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldSkipProperty(PropertyMetadata $property, Context $context)
    {
        if (!empty($this->classes)) {
            if (isset($this->classes[$property->class])) {
                $name = $property->serializedName ?: $property->name;
                return in_array($name, $this->classes[$property->class]);
            }
        }

        return false;
    }
}
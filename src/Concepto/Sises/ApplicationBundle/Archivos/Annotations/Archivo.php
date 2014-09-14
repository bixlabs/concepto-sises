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

namespace Concepto\Sises\ApplicationBundle\Archivos\Annotations;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Archivo
 * @package Concepto\Sises\ApplicationBundle\Archivos\Annotations
 * @Annotation
 * @Target("PROPERTY")
 */
class Archivo {
    private $type = 'one';
    private $name;

    function __construct($options)
    {
        if (isset($options['value'])) {
            $options['name'] = $options['value'];
            unset($options['value']);
        }

        foreach ($options as $key => $option) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException(
                    sprintf("Propiedad '%s' no existe", $key)
                );
            }

            $this->$key = $option;
        }

        $valid_types = array('one', 'many');

        if (!in_array($this->type, $valid_types)) {
            throw new \InvalidArgumentException(sprintf(
                "La propiedad 'type' debe ser (%s), pero se recibio '%s'",
                implode(', ', $valid_types),
                $this->type
            ));
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}
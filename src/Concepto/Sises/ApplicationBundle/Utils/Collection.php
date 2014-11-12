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

namespace Concepto\Sises\ApplicationBundle\Utils;


class Collection {
    public static function buildQuery($collection, $default = '-1')
    {
        $_e = array();

        foreach ($collection as $object) {
            $_e[] = $object->getId();
        }

        if (count($_e) > 0) {
            return 'A,' . implode(';', $_e);
        } else {
            return $default;
        }
    }
} 
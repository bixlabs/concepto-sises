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

namespace Concepto\Sises\ApplicationBundle\Entity;


class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllBy($parameters)
    {
        $qb = $this->createQueryBuilder('t');

        foreach($parameters as $key => $parameter) {
            $comp = $this->extractComparator($parameter);
            $qb->andWhere("t.{$key} {$comp[0]} :{$key}");
            $qb->setParameter($key, $comp[1]);
        }

        return $qb->getQuery()->execute();
    }

    private function extractComparator($value)
    {
        if (preg_match('/,/', $value)) {
            $explodedValue = explode(',', $value);

            switch($explodedValue[0]) {
                case 'L':
                    $explodedValue[0] = 'LIKE';
                    $explodedValue[1] = "%{$explodedValue[1]}%";
                    break;
            }

            return $explodedValue;
        }

        return array('=', $value);
    }
}
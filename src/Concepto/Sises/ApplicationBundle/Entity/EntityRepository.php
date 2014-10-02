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
    public function findAll($parameters = null)
    {
        if (!$parameters || count($parameters) == 0) {
            return parent::findAll();
        }

        $qb = $this->createQueryBuilder('t');

        foreach($parameters as $key => $parameter) {
            $comp = $this->extractComparator($parameter);

            // Only for non-boolean values
            if (!is_bool($comp[1])) {
                // If some parameter is empty or null or empty array
                if (empty($comp[1]) || is_null($comp[1]) || count($comp[1]) == 0) {
                    return [];
                }
            }

            // Case especial uuid
            $key = $key == 'uuid' ? 'id': $key;

            if (is_array($comp[1])) {
                $qb->andWhere($qb->expr()->in("t.{$key}", ":{$key}"));
            } else {
                $qb->andWhere("t.{$key} {$comp[0]} :{$key}");
            }

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
                case 'A':
                    $explodedValue[0] = 'IN';
                    $explodedValue[1] = explode(';', $explodedValue[1]);
                    break;
            }

            return $explodedValue;
        }

        switch($value) {
            case 'true':
                $value = true;
                break;
            case 'false':
                $value = false;
                break;
        }

        return array('=', $value);
    }
}
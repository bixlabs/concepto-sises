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


use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

class EntityRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllQueryBuilder($parameters = null)
    {
        if (!$parameters || count($parameters) == 0) {
            return parent::findAll();
        }

        $mainAlias = 't';
        $qb = $this->createQueryBuilder($mainAlias);

        foreach($parameters as $key => $parameter) {
            $alias = $mainAlias;
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

            if (preg_match('/__/', $key)) {
                $joinInfo = explode('__', $key);
                $key = $joinInfo[1];
                $alias = uniqid('alias');
                $qb->leftJoin("{$mainAlias}.{$joinInfo[0]}", $alias);
            }

            if (is_array($comp[1])) {
                $qb->andWhere($qb->expr()->in("{$alias}.{$key}", ":{$key}"));
            } else {
                $qb->andWhere("{$alias}.{$key} {$comp[0]} :{$key}");
            }

            $qb->setParameter($key, $comp[1]);
        }

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param $joinProperty
     * @param bool $makeJoin
     * @param null $rootAlias
     *
     * @return null|string
     */
    protected function findJoinAlias(&$qb, $joinProperty, $makeJoin = true, $rootAlias = null)
    {
        if (!$rootAlias) {
            $rootAlias = $qb->getRootAliases()[0];
        }

        $foundAlias = null;

        foreach ($qb->getDQLPart('join') as $joinArray) {
            /** @var Join $join */
            $join = reset($joinArray);
            $name = str_replace($rootAlias . '.', '', $join->getJoin());

            if ($name == $joinProperty) {
                $foundAlias = $join->getAlias();
                break;
            }
        }

        if (!$foundAlias && $makeJoin) {
            $foundAlias = uniqid('alias');
            $qb->leftJoin("{$rootAlias}.{$joinProperty}", $foundAlias);
        }

        return $foundAlias;
    }

    public function findAll($parameters = null)
    {
        $qb = $this->findAllQueryBuilder($parameters);

        if (is_array($qb)) {
            return $qb;
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
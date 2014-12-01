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

namespace Concepto\Sises\ApplicationBundle\Utils\C3;


class Utils {
    /**
     * Conviert el resultado de un query en formato c3.js
     *
     * @param $keyColumn "Indica cual es el eje x"
     * @param $results "Resultados"
     * @param array|mixed $query
     * @return array      "Formato c3.js"
     */
    public static function calculec3($keyColumn, $results, $query = array())
    {
        $columns = array(
            // El primer valor siempre es la fecha
            $keyColumn => array($keyColumn)
        );

        $names = array();

        foreach ($results as $result) {
            // Buscamos el indice de la fecha
            $dateIdx = array_search($result[$keyColumn], $columns[$keyColumn]);

            if (!$dateIdx) {
                $columns[$keyColumn][] = $result[$keyColumn];
                $dateIdx = count($columns[$keyColumn]) -1;
            }

            if (!isset($columns[$result['id']])) {
                // El primer valor del array es el id
                $columns[$result['id']] = array($result['id']);
                // Nombre para la traduccion
                $names[$result['id']] = $result['nombre'];
            }

            // El valor debe ir en el mismo lugar que la fecha a la que pertenece
            $columns[$result['id']][$dateIdx] = (int)$result['total'];
        }

        // Se asegura de colocar zero donde sea necesario
        foreach (array_keys($columns) as $id) {
            // se ignora las fechas
            if ($id === $keyColumn) {
                continue;
            }

            foreach (array_keys($columns[$keyColumn]) as $index) {
                if (!isset($columns[$id][$index])) {
                    $columns[$id][$index] = 'null';
                }
            }

            // Las llaves fueron agregadas en desorden, se organizan
            ksort($columns[$id]);
        }

        return array(
            'data' => array(
                'columns' => array_values($columns),
                'names' => $names
            ),
            'query' => $query
        );
    }
} 
/**
 * Copyright © 2014 Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 *
 * This file is part of concepto-sises.
 *
 * concepto-sises
 * can not be copied and/or distributed without the express
 * permission of Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 */
;
(function () {
    "use strict";

    var module = G.BuildModule('PLANILLAS', {
        register: 'planillas',
        label: 'Planillas',
        category: 'entrega_category',
        states: [
            {
                suffix: '',
                templateUrl: G.template('planillas/index'),
                controller: 'PlanillasIndex',
                url: '/planillas'
            }
        ]
    });

    module.controller('PlanillasIndex', ['$scope', function _planillasIndex(scope) {
        scope.seleccion = {
            asignacion: null
        };

        scope.fechas = [];

        scope.getLink = function getLink(item) {
            return G.route('concepto_pdf', {
                id: scope.seleccion.asignacion.id,
                date: item.date
            });
        };

        scope.$watch('seleccion.asignacion', function (val) {
            if (!val) {
                return;
            }

            scope.fechas = [];
            var start = moment(val.fechas.inicio).startOf('month'),
                end = moment(val.fechas.cierre).endOf('month');

            while (start.isBefore(end)) {

                scope.fechas.push({
                    date: start.format('YYYY-MM'),
                    display: start.format('MMMM [de] YYYY')
                });
                start.add(1, 'month');
            }
        });
    }]);
})();
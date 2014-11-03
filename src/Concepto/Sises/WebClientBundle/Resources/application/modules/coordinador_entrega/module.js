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

    //G.Base.Controller
    var module = G.BuildModule('COORD_ENTREGA', {
        register: 'coord_entrega',
        label: 'Realizar entrega',
        states: [
            {
                suffix: '',
                templateUrl: G.template('coord_entrega/index'),
                controller: 'CEIndex',
                url: '/entregas'
            }
        ]
    });


    module.controller('CEIndex', [
        'RestResources', '$scope', '$http',
        function(RR, scope, $http) {
            scope.seleccion = {
                asignacion: null,
                now: null
            };

            scope.pagination = {
                items: [],
                total: 0,
                max: 25,
                page: 1
            };

            scope.listado = [];
            scope.entregas = {};

            // Devuelve el numero para el registro mostrado
            scope.getNumber = function getNumber(index) {
                var p = scope.pagination;

                return ((p.page-1) * p.max) + index + 1;
            };

            // Carga la asignacion
            scope.$watch('seleccion.asignacion_id', function cargaAsignacion(id) {
                if (id) {
                    scope.seleccion.asignacion = RR.coordinador_entrega.get({'id': id});
                    scope.listado = [];
                }
            });

            scope.$watch('listado', function(items) {
                scope.pagination.page = 1;
                scope.updatePagination();
            });

            scope.updatePagination = function updatePagination() {
                var p = scope.pagination,
                    offset = (p.page -1) * p.max,
                    end;

                scope.pagination.total = scope.listado.length;

                end = Math.min(scope.pagination.total, offset + p.max);
                scope.pagination.items =
                    scope.listado.length > 0 ? scope.listado.slice(offset, end): [];
            };

            // Actualiza el listado al seleccionar fecha
            scope.$watch('seleccion.now', function(now) {
                if (now) {
                    var id = scope.seleccion.asignacion.id;
                    $http.post(G.route('post_asignacion_entrega'), {
                        id: id,
                        fecha: moment(now).format('YYYY-MM-DDTHH:mm:ssZZ')
                    }).success(function(data) {
                        scope.seleccion.asignacion = RR.coordinador_entrega.get({'id': id});
                        scope.listado = data;
                    });
                }
            });
        }
    ]);

})();
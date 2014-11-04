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

            var guardar = true;

            scope.seleccion = {
                asignacion: null,
                now: null
            };

            scope.fecha = {
                inicio: null,
                cierre: null,
                all: false
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
                    scope.seleccion.asignacion = RR.coordinador_entrega.get({'id': id}, function() {
                        var e = scope.seleccion.asignacion.entrega;
                        scope.fecha = {
                            cierre: moment(e.fechaInicio).startOf('day').format('LLL'),
                            inicio: moment(e.fechaCierre).endOf('day').format('LLL'),
                            all: false
                        };
                    });
                    scope.listado = [];
                }
            });

            scope.$watch('listado', function() {
                scope.pagination.page = 1;
                scope.updatePagination();
            });

            scope.getItems = function getItems() {
                return scope.pagination.items;
            };

            scope.updatePagination = function updatePagination() {
                var p = scope.pagination,
                    offset = (p.page -1) * p.max,
                    end;

                scope.pagination.total = scope.listado.length;

                end = Math.min(scope.pagination.total, offset + p.max);
                scope.pagination.items =
                    scope.listado.length > 0 ? scope.listado.slice(offset, end): [];
            };

            scope.puedeGuardar = function puedeGuardar() {
                return !guardar;
            };

            scope.getEstado = function getEstado() {

                if (scope.seleccion && scope.seleccion.asignacion) {
                    /** @namespace scope.seleccion.asignacion.is_cierre_manual */
                    return scope.seleccion.asignacion.is_cierre_manual ? "Cerrada" : "Pendiente";
                }

                return '';
            };

            /**
             * Envia los datos de la entrega al servidor
             */
            scope.guardarEntrega = function guardarEntrega() {
                guardar = false;
                var entregas = [];
                angular.forEach(scope.entregas, function(entrega) {
                    entregas.push(entrega);
                });

                $http.post(G.route('post_asignacion_realiza'), {
                    entregas: entregas
                }).success(function() {
                    guardar = true;
                }).error(function() {
                    guardar = true;
                });
            };

            scope.getCurNow = function() {
                if (scope._now) {
                    return scope._now.format('LLL');
                }
            };

            scope.$watch('fecha.all', function() {
                angular.forEach(scope.getItems(), function(item) {
                    scope.entregas[item.id].estado = scope.fecha.all;
                });
            });

            // Actualiza el listado al seleccionar fecha
            scope.$watch('seleccion.now', function(now) {
                if (now) {
                    scope._now = moment(now);
                    var id = scope.seleccion.asignacion.id;
                    $http.post(G.route('post_asignacion_entrega'), {
                        id: id,
                        fecha: scope._now.format('YYYY-MM-DDTHH:mm:ssZZ')
                    }).success(function(data) {
                        scope.seleccion.asignacion = RR.coordinador_entrega.get({'id': id});
                        scope.entregas = {};

                        var all = true;

                        angular.forEach(data, function(item) {
                            /** @namespace item.estado */
                            /** @namespace item.id */
                            scope.entregas[item.id] = {
                                id: item.id,
                                estado: item.estado
                            };

                            all = all && item.estado;
                        });

                        scope.listado = data;
                        scope.fecha.all = all;
                    });
                }
            });
        }
    ]);

})();
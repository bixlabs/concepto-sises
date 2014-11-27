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

    G.modules.ADMIN_ENTREGA = 'ADMIN_ENTREGA';

    var STATE = {
        OPEN :'pendiente',
        EDITING :'modificado',
        CLOSE: 'finalizada',
        CLOSING: 'cerrando'
    };

    G.BuildModule(G.modules.ADMIN_ENTREGA, {
        register: true,
        label: 'Gestión de entregas',
        category: 'entrega_category',
        controllers: {
            edit: {
                deps: ['$http', 'ngToast'],
                func: function(RR, scope, $http, ngToast) {
                    scope.detalles = [];
                    scope.detalles_cierre = {};
                    scope.extra = {
                        observacion: ""
                    };

                    scope.calcular = function calcular() {
                        scope.element.estado = STATE.CLOSING;
                        _getDetalles();
                    };

                    /**
                     * Obtiene los detalles del servidor
                     * @private
                     */
                    function _getDetalles() {
                        RR.admin_entrega_calcular.get({id: scope.element.id}, function(data) {
                            scope.detalles = data.results;
                            scope.detalles_cierre = {};
                            angular.forEach(data.results, function(item) {
                                scope.detalles_cierre[item.id] = {
                                    id: item.id,
                                    cantidad: item.total
                                };
                            });
                        });
                    }

                    /**
                     * Verifica que la observacion exista si se esta editando
                     * @returns {boolean}
                     * @private
                     */
                    function _failCheckObservation() {


                        console.log("Editando", scope.isEditing(), "Observacion:", scope.extra.observacion);
                        scope.extra.observacion.trim();

                        if (scope.isEditing() && scope.extra.observacion.length === 0) {
                            ngToast.create({
                                'content': '<i class="glyphicon glyphicon-exclamation-sign"></i> La observacion es obligatoria',
                                'class': 'danger',
                                'verticalPosition': 'top',
                                'horizontalPosition': 'center'
                            });

                            return true;
                        }

                        return false;
                    }

                    /**
                     * Envia los detalles al servidor y la observacion si existe
                     * @private
                     */
                    function _saveDetalles() {

                        if (_failCheckObservation()) {
                            return;
                        }

                        var servicios = [];

                        angular.forEach(scope.detalles_cierre, function(servicio) {
                            servicios.push(servicio);
                        });

                        $http.put(G.route('put_entrega_cierre'), {
                            id: scope.element.id,
                            servicios: servicios,
                            observacion: scope.extra.observacion
                        }).success(function() {
                            scope.details(scope.element.id);
                        })
                    }

                    scope.cancelarCierre = function cancelarCierre() {
                        scope.element.estado = STATE.OPEN;
                    };

                    scope.okCierre = _saveDetalles;

                    scope.modificar = function modificar() {
                        scope.extra.observacion = "";
                        scope.element.estado = STATE.EDITING;
                        _getDetalles();
                    };

                    scope.cancelEditing = function _cancelEditing() {
                        scope.element.estado = STATE.CLOSE;
                    };
                    scope.okEditing = _saveDetalles;

                    scope.isNew = function _isNew() {
                        return !!scope.element.estado;
                    };

                    scope.isOpen = function _isOpen() {
                        return scope.element.estado === STATE.OPEN;
                    };

                    scope.isClosing = function _isClosing() {
                        return scope.element.estado === STATE.CLOSING;
                    };

                    scope.isClosed = function _isClosed() {
                        return scope.element.estado === STATE.CLOSE;
                    };

                    scope.isEditing = function _isEditing() {
                        return scope.element.estado === STATE.EDITING;
                    };

                    scope.$watch('element.estado', function(val) {
                        if (val && val === 'finalizada') {
                            $http.get(G.route('get_entrega_detalles', {
                                id: scope.element.id
                            })).success(function(data) {
                                scope.detalles = data;
                            });
                        }
                    });
                }
            }
        }
    });
})();
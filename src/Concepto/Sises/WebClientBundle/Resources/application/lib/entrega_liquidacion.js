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

    var STATE = {
        OPEN :'pendiente',
        EDITING :'modificado',
        CLOSED: 'finalizada',
        CLOSING: 'cerrando'
    };

    function BaseEntrega(RR, scope, $http, ngToast) {
        scope.detalles = [];
        scope.detalles_cierre = {};
        scope.extra = {
            observacion: ""
        };

        var that = this;

        scope.calcular = function calcular() {
            that._calcular(_buildDetalles);
        };

        /**
         * Construye los detalles de la tabla
         * @private
         */
        function _buildDetalles(data) {
            scope.element.estado = STATE.CLOSING;
            scope.detalles = data;
            scope.detalles_cierre = {};
            angular.forEach(data, function(item) {
                scope.detalles_cierre[item.servicio] = {
                    servicio: item.servicio,
                    cantidad: item.cantidad
                };
            });
        }

        /**
         * Verifica que la observacion exista si se esta editando
         * @returns {boolean}
         * @private
         */
        function _failCheckObservation() {
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

            angular.forEach(
                scope.detalles_cierre,
                function(servicio) { servicios.push(servicio); }
            );

            $http
                .put(G.route(that._routeCierre()), that._buildSave(servicios))
                .success(function() { scope.details(scope.element.id); });
        }

        /**
         * Ruta para enviar el cierre
         * @private
         */
        this._routeCierre = function _routeCierre() {
            throw "Not implemented";
        };

        /**
         * Ruta para obtener detalles
         * @private
         */
        this._routeDetalles = function _routeDetalles() {
            throw "Not implemented";
        };

        /**
         * Construye el envio al servidor
         * @param servicios
         * @private
         */
        this._buildSave = function _buildSave(servicios) {
            throw "Not implemented";
        };

        /**
         * Obtiene los detalles calculados del servidor
         * @param callback
         * @private
         */
        this._calcular = function _calcular(callback) {
            throw  "No implementado";
        };

        scope.cancelarCierre = function cancelarCierre() {
            scope.element.estado = STATE.OPEN;
        };

        scope.okCierre = _saveDetalles;

        scope.modificar = function modificar() {
            scope.extra.observacion = "";
            scope.element.estado = STATE.EDITING;
        };

        scope.cancelEditing = function _cancelEditing() {
            scope.element.estado = STATE.CLOSED;
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
            return scope.element.estado === STATE.CLOSED;
        };

        scope.isEditing = function _isEditing() {
            return scope.element.estado === STATE.EDITING;
        };

        scope.$watch('element.estado', function(val) {
            if (val && val === STATE.CLOSED) {
                $http.get(G.route(that._routeDetalles(), {
                    id: scope.element.id
                })).success(function(data) {
                    scope.detalles = data;
                    _buildDetalles(data);
                });
            }
        });
    }

    G.Base.BaseEntrega = BaseEntrega;
})();
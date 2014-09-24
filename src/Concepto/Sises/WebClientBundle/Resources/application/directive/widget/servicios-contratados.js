/**
 * Created by julian on 9/09/14.
 */
;
(function () {
    "use strict";

    angular.module(G.APP)

        .directive('sisesServiciosContratados', ['$rootScope', function($r) {
            return {
                restrict: 'A',
                replace: true,
                templateUrl: G.template('directive_servicios_contratados'),
                scope: {
                    elements: '=sisesServiciosContratados'
                },
                link: function(scope) {
                    var id = G.guid();
                    scope.errors = {};
                    scope.element = {
                        id: G.guid()
                    };
                    scope.handler = {
                        id: id,
                        actions: {
                            ok: {
                                label: 'Agregar',
                                style: 'primary'
                            },
                            cancel: {
                                dismiss: true,
                                label: 'Volver'
                            }
                        }
                    };

                    var findService = function(service) {
                        var result = -1;

                        angular.forEach(scope.elements, function(value, index) {
                            if (value.id === service.id) {
                                result = index;
                            }
                        });

                        return result;
                    };

                    $r.$on('modalize.action.' + id, function(event, data) {
                        switch (data) {
                            case "ok":
                                if (!scope.elements) {
                                    scope.elements = [];
                                }
                                var element = angular.extend({}, scope.element),
                                    index = findService(element);

                                if (index < 0) {
                                    scope.elements.push(element);
                                } else {
                                    scope.elements[index] = element;
                                }

                                scope.element = {
                                    id: G.guid()
                                };
                                scope.handler.hide();
                                break;
                        }
                    });

                    scope.getValorBruto = function(servicio) {
                        return servicio.dias_contratados
                            * servicio.unidades_diarias
                            * servicio.valor_unitario;
                    };

                    scope.getValorCostos = function(servicio) {
                        return servicio.dias_contratados
                            * servicio.unidades_diarias
                            * servicio.costo_unitario;
                    };

                    scope.getCurrentTitle = function() {
                        return scope.element.title ? "Actualizar servicio" : "Agregar servicio";
                    };

                    scope.add = function() {
                        scope.element = {};
                        scope.handler.show();
                    };

                    scope.edit = function(servicio) {
                        scope.element = angular.extend({}, servicio);
                        scope.handler.show();
                    };

                    scope.remove = function(servicio) {
                        var index = findService(servicio);

                        if (index >= 0) {
                            scope.elements.splice(index, 1)
                        }
                    };
                }
            };
        }])
})();
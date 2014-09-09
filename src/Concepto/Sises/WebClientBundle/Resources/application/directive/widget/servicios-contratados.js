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

                    scope.errors = {};
                    scope.element = {};
                    scope.handler = {
                        id: 'ss',
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
                            if (value.nombre === service.nombre) {
                                result = index;
                            }
                        });

                        return result;
                    };

                    $r.$on('modalize.action.ss', function(event, data) {
                        switch (data) {
                            case "ok":
                                if (!scope.elements) {
                                    scope.elements = [];
                                }
                                scope.elements.push(angular.extend({}, scope.element));
                                scope.element = {};
                                scope.handler.hide();
                                break;
                        }
                    });

                    scope.getPrecio = function(servicio) {
                        return servicio.dias_contratados
                            * servicio.unidades_diarias
                            * servicio.valor_unitario;
                    };

                    scope.add = function() {
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
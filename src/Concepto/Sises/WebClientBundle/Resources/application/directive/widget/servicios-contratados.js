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
                scope: true,
                link: function(scope) {

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

                    $r.$on('modalize.action.ss', function(event, data) {
                        console.debug('Llego', data);
                    });

                    scope.getPrecio = function(servicio) {
                        return 0;
                    };

                    scope.add = function() {
                        scope.handler.show();
                    };
                }
            };
        }])
})();
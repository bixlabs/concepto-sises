/**
 * Created by julian on 16/09/14.
 */
;
(function () {
    "use strict";
    angular.module(G.APP)
        .directive('sisesWidgetBeneficios', ['RestResources', '$rootScope', function(RR, $r) {
            return {
                restricti: 'A',
                templateUrl: G.template('directive/widget_beneficios'),
                link: function(scope) {
                    scope.id = G.guid();

                    // TODO: Debe llamarse desde crud
                    scope.contratos = RR.contrato.query();

                    scope.handler = {
                        id: scope.id,
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

                    $r.$on('modalize.action.' + scope.id, function(event, data) {
                        switch (data) {
                            case "ok":
                                console.log(scope.element);
                                break;
                            default:
                                throw 'Respuesta no capturada' + data;
                        }
                    });

                    scope.add = function() {
                        scope.handler.show();
                    };
                }
            };
        }])
    ;
})();
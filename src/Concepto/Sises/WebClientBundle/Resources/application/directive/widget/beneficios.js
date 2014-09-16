/**
 * Created by julian on 16/09/14.
 */
;
(function () {
    "use strict";
    angular.module(G.APP)
        .directive('sisesWidgetBeneficios', ['RestResources', function(RR) {
            return {
                restricti: 'A',
                templateUrl: G.template('directive/widget_beneficios'),
                link: function(scope) {
                    scope.id = G.guid();

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

                    scope.add = function() {
                        scope.handler.show();
                    };

                    var append = function(archivo) {

                        var founded = -1;

                        // Se busca por archivos, tiene nombres unicos
                        angular.forEach(scope.elements, function(value, index) {
                            if (value.file === archivo.file) {
                                founded = index;
                            }
                        });

                        if (founded < 0) {
                            if (!scope.elements) {
                                scope.elements = [];
                            }
                            scope.elements.push(archivo);
                        }
                    };
                }
            };
        }])
    ;
})();
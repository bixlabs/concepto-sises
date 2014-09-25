/**
 * Created by julian on 16/09/14.
 */
;
(function () {
    "use strict";

    var CommonListElement = function(scope, findCallback) {

        if (!scope) {
            throw "Need pass scope as first argument!";
        }

        if (!findCallback) {
            throw "Need define a function() to compare";
        }

        var find = function(element) {
            var founded = -1;

            // Se busca por archivos, tiene nombres unicos
            angular.forEach(scope.elements, function(value, index) {
                founded = findCallback.call(this, value, index, element);
            });

            return founded;
        };

        scope.removeElement = function(element, $event) {
            $event && $event.stopPropagation();

            var found = find(element);

            if (found !== -1) {
                scope.elements.splice(found, 1);
            }
        };

        scope.addElement = function(element, $event) {
            $event && $event.stopPropagation();

            var found = find(element);


            if (found === -1) {
                scope.elements = scope.elements || [];
                scope.elements.push(element);
            }
        }
    };
    angular.module(G.APP)
        .directive('sisesWidgetBeneficios', ['RestResources', '$rootScope', function(RR, $r) {
            return {
                restricti: 'A',
                templateUrl: G.template('directive/widget_beneficios'),
                scope: {
                    'elements': '=sisesWidgetBeneficios'
                },
                link: function(scope) {
                    scope.id = G.guid();

                    // TODO: Debe llamarse desde crud
                    scope.contratos = RR.contrato.query();
                    scope.element = {};

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

                    CommonListElement.call(this, scope, function(value, index, element) {
                        if (value.servicio === element.servicio && value.lugar === element.lugar) {
                            return index;
                        }
                    });

                    $r.$on('modalize.action.' + scope.id, function(event, data) {
                        switch (data) {
                            case "ok":
                                // Contrato no es necesario
                                if (scope.element['contrato']) {
                                    delete scope.element['contrato'];
                                }

                                scope.addElement(scope.element);
                                scope.handler.hide();
                                break;
                            default:
                                break;
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
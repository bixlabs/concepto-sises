/**
 * Created by julian on 15/09/14.
 */
;
(function () {
    "use strict";

    angular.module(G.APP)
        .directive('sisesWidgetArchivos', [function() {
            return {
                templateUrl: G.template('directive/widget_archivos'),
                scope: {
                    elements: '=sisesWidgetArchivos'
                },
                link: function(scope) {

                    scope.active = false;
                    scope.percent = 0;
                    scope.files = [];

                    var append = function(archivo) {

                        var founded = -1;

                        angular.forEach(scope.elements, function(value, index) {
                            if (value.nombre === archivo.nombre) {
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

                    scope.fileUploaded = function(res) {
                        scope.active = false;
                        append({
                            file: JSON.parse(res.response).file,
                            nombre: scope.files[0].name
                        });
                        scope.files = [];
                    };

                    scope.fileAdded = function() {
                        scope.percent = 0;
                        scope.active = true;
                    };
                }
            }
        }])
    ;
})();
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

                    scope.id = G.guid();
                    scope.active = false;
                    scope.percent = 0;
                    scope.files = [];
                    scope.current_edit = null;

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

                    scope.edit = function(archivo) {
                        scope.current_edit = archivo;
                    };
                    scope.editOff = function($event) {
                        scope.current_edit = null;
                        $event.stopPropagation()
                    };

                    scope.editable = function(archivo) {
                        if (!scope.current_edit) {
                            return false;
                        }

                        return scope.current_edit.file === archivo.file;
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
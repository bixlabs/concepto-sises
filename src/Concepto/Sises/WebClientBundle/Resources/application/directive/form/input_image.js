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

    angular.module(G.APP)
    /**
     * directive sisesFormImage
     */
        .directive('sisesFormImage', ['$timeout', function($t) {
            return {
                restrict: 'A',
                replace: true,
                require: ['^sisesForm', '?^sisesCompound'],
                templateUrl: G.template('directive/form_image'),
                scope: {
                    property: '@sisesFormImage'
                },
                link: function(scope, el, attrs, controllers) {

                    var defaultImage = '/bundles/siseswebclient/images/logo-default.png',
                        baseUploads = '/uploads/documentable/',
                        defaultLabel = 'Subir imagen',
                        updateImage;

                    G.Form.InputFormLink.call(this, scope, el, attrs, controllers);

                    // Si no se define se usa el label por defecto
                    scope.formProperties.buttonLabel = !attrs.buttonLabel ? defaultLabel : scope.$eval(attrs.buttonLabel);

                    // Propiedades del cargador
                    scope.formProperties = angular.extend(scope.formProperties, {
                        uploader: {},
                        active: false,
                        percent: 0
                    });

                    // Actualiza la image si es necesario
                    scope.$watch('form.model.' + scope.property, function(val) {
                        updateImage(val);
                    });

                    // Actualiza la imagen a mostrar
                    updateImage = function(imgPath) {
                        scope.formProperties.logo = imgPath ? (baseUploads + imgPath) : defaultImage;
                    };

                    // Obtiene el nombre del archivo cargado
                    scope.fileUploaded = function(res) {
                        scope.form.model[scope.property] = JSON.parse(res.response).file;

                        // Pequeño delay para lograr observar el funcionamiento
                        $t(function() {
                            scope.formProperties.active = false;
                            scope.formProperties.percent = 0;
                        }, 100);
                    };

                    // Se detecta un archivo nuevo, se muestra la barra de carga
                    scope.fileAdded = function() {
                        scope.formProperties.active = true;
                        scope.formProperties.percent = 0;
                    };
                }
            };
        }])
    ;
})();
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
        .directive('sisesUsuario', [
            '$http',
            function sisesUsuario_directive($http) {
                return {
                    restrict: 'AEC',
                    replace: true,
                    templateUrl: G.template('directive/widget_usuario'),
                    scope: {
                        element: '=sisesUsuario'
                    },
                    link: function sisesUsuario_link(scope) {
                        scope.usuario = {
                            password: {}
                        };
                        scope.$watch('element', function(element) {
                            if (element && element.id) {
                                scope.usuario.related = element.id;
                            }
                        }, true);

                        scope.save = function _sisesUsuario_save() {
                            $http
                                .post(G.route('post_usuario_coordinador'), scope.usuario)
                                .success(function _sisesUsuario_save_success() {

                                });
                        };
                    }
                };
            }
        ])
    ;
})();
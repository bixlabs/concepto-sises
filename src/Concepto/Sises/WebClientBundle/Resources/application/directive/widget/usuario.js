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
                        element: '=sisesUsuario',
                        type: '@'
                    },
                    link: function sisesUsuario_link(scope) {
                        scope.usuario = {
                            password: {}
                        };
                        scope.loaded = false;
                        scope.reasonEmail = false;
                        scope.reasonUsername = false;

                        scope.disableSave = function() {
                            return scope.reasonEmail || scope.reasonUsername;
                        };

                        function _checkUsername() {
                            if (
                                scope.usuario.username
                                && scope.usuario.username.length > 3
                                && !scope.loaded
                            ) {
                                $http
                                    .get(G.route('get_usuario_check', {
                                        username: scope.usuario.username ? scope.usuario.username : null,
                                        email: scope.usuario.email ? scope.usuario.email : null
                                    }))
                                    .success(function(data, status, headers) {
                                        switch (headers('X-Can-Use-Email')) {
                                            case 'no':
                                                scope.reasonEmail = true;
                                                break;
                                            default:
                                                scope.reasonEmail = false;
                                                break;
                                        }

                                        switch (headers('X-Can-Use-Username')) {
                                            case 'no':
                                                scope.reasonUsername = true;
                                                break;
                                            default:
                                                scope.reasonUsername = false;
                                                break;
                                        }
                                    });
                            }
                        }

                        function _loadUserInfo() {
                            if (scope.element && scope.element.id) {
                                scope.usuario.related = scope.element.id;
                                $http
                                    .get(G.route('get_usuario', {id: scope.usuario.related}))
                                    .success(function(data) {
                                        if (typeof data.username !== 'undefined') {
                                            scope.usuario = angular.extend(scope.usuario, data);
                                            scope.loaded = true;
                                            scope.usuario.password = {};
                                        }
                                    });
                            }
                        }
                        scope.$watch('element', _loadUserInfo, true);
                        scope.$watch('usuario.username', _checkUsername);
                        scope.$watch('usuario.email', _checkUsername);

                        scope.save = function _sisesUsuario_save() {
                            $http
                                .post(G.route('post_usuario_' + scope.type), scope.usuario)
                                .success(function _sisesUsuario_save_success() {
                                    _loadUserInfo();
                                });
                        };
                    }
                };
            }
        ])
    ;
})();
/**
 * Created by julian on 4/09/14.
 */
(function() {
    "use strict";

    var json_route = function (name) {
        return Routing.getBaseUrl() + (name);
    };

    var template = function (name) {
        return Routing.generate('view_partials', {name: name});
    };

    angular.module('sises', ['ngRoute' ,'ngResource'])
        .config(['$routeProvider', function ($routeProvider) {
            $routeProvider
                .when('/empresas', {
                    controller: 'EmpresaController',
                    templateUrl: template('listado_empresa')
                })
                .when('/empresas/nueva', {
                    controller: 'EmpresaNuevaController',
                    templateUrl: template('empresa_nueva')
                })
                .when('/empresas/:id', {
                    controller: 'EmpresaVerController',
                    templateUrl: template('empresa_ver')
                })
                .otherwise({redirectTo: '/empresas'})
            ;
        }])

        .factory('Empresa', ['$resource', function($r) {
            return $r(json_route('/api/empresas/:id.json'), { id: '@id' }, {
                update: { method: 'PUT'}
            }, {
                stripTrailingSlashes: false
            });
        }])

        .controller('EmpresaController', ['$scope', 'Empresa', function($s, Empresa) {
            $s.empresas = Empresa.query();
        }])

        .controller('EmpresaVerController', ['$scope', 'Empresa', '$location', '$routeParams',function($s, Empresa, $l, $p) {
            $s.errors = {};
            $s.empresa = Empresa.get({id: $p.id});

            var canSave = true;
            var canRemove = true;

            $s.hasError = function(name) {
                return $s.errors[name] && $s.errors[name].errors && $s.errors[name].errors.length;
            };

            $s.canSave = function() {
                return canSave;
            };

            $s.eliminarEmpresa = function() {
                canRemove = false;
                $s.empresa.$delete(function() {
                    $l.path('/empresas')
                }, function (response) {
                    console.error(response);
                    canRemove = true;
                });
            };

            $s.guardarEmpresa = function() {
                canSave = false;
                $s.empresa.$update(function() {
                    $l.path('/empresa/' + $s.empresa.id)
                }, function(response) {
                    switch (response.data.code) {
                        case 400:
                            $s.errors = response.data.errors.children;
                            break;
                        default:
                            console.error(response);
                            break;
                    }

                    canSave = true;
                });
            };
        }])

        .controller('EmpresaNuevaController', ['$scope', 'Empresa', '$location', function($s, Empresa, $l) {
            $s.empresa = new Empresa();
            $s.errors = {};

            var canSave = true;

            $s.hasError = function(name) {
                return $s.errors[name] && $s.errors[name].errors && $s.errors[name].errors.length;
            };

            $s.canSave = function() {
                return canSave;
            };

            $s.guardarEmpresa = function() {
                canSave = false;
                $s.empresa.$save(function () {
                    $l.path('/empresa/' + $s.empresa.id)
                }, function(response) {
                    switch (response.data.code) {
                        case 400:
                            $s.errors = response.data.errors.children;
                            break;
                        default:
                            console.error(response);
                            break;
                    }
                    canSave = true;
                })
            };
        }])

        .run(['$rootScope', '$location', function ($r, $l) {
            $r.go = function (path) {
                $l.path(path);
            };
            $r.template = template;
        }])
    ;
})();
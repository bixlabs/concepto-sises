/**
 * Created by julian on 4/09/14.
 */
(function() {
    "use strict";

    var json_route = function (name) {
        return Routing.getBaseUrl() + (name);
    };

    angular.module('sises', ['ngRoute' ,'ngResource'])
        .config(['$routeProvider', function ($routeProvider) {
            $routeProvider
                .when('/empresas', {
                    controller: 'EmpresaController',
                    templateUrl: '/bundles/siseswebclient/templates/listado_empresa.html'
                })
                .when('/empresas/nueva', {
                    controller: 'EmpresaNuevaController',
                    templateUrl: '/bundles/siseswebclient/templates/empresa_nueva.html'
                })
                .otherwise({redirectTo: '/empresas'})
            ;
        }])

        .factory('Empresa', ['$resource', function($r) {
            return $r(json_route('/api/empresas/:id.json'), { id: '@_id' });
        }])

        .controller('EmpresaController', ['$scope', 'Empresa', function($s, Empresa) {
            $s.empresas = Empresa.query();
        }])

        .controller('EmpresaNuevaController', ['$scope', 'Empresa', '$location', function($s, Empresa, $l) {
            $s.empresa = new Empresa();
            $s.errors = {};

            $s.hasError = function(name) {
                return $s.errors[name] && $s.errors[name].errors.length;
            };

            $s.guardarEmpresa = function() {
                $s.empresa.$save(function () {
                    $l.path('/empresas');
                }, function(response) {
                    $s.errors = response.data.errors.children;
                })
            };
        }])

        .run(['$rootScope', '$location', function ($r, $l) {
            $r.go = function (path) {
                $l.path(path);
            };
        }])
    ;
})();
/**
 * Created by julian on 4/09/14.
 */
(function() {
    "use strict";

    angular.module(G.APP, ['ngRoute' ,'ngResource'])
        .config(['$routeProvider', function ($routeProvider) {
            $routeProvider
                .when('/empresas', {
                    controller: 'EmpresaController',
                    templateUrl: G.template('listado_empresa')
                })
                .when('/empresas/nueva', {
                    controller: 'EmpresaNuevaController',
                    templateUrl: G.template('empresa_nueva')
                })
                .when('/empresas/:id', {
                    controller: 'EmpresaVerController',
                    templateUrl: G.template('empresa_ver')
                })
                .otherwise({redirectTo: '/empresas'})
            ;
        }])

        .factory('Empresa', ['$resource', function($r) {
            return $r(G.json_route('/api/empresas/:id.json'), { id: '@id' }, {
                update: { method: 'PUT'}
            }, {
                stripTrailingSlashes: false
            });
        }])

        .run(['$rootScope', '$location', 'MenuService', function ($r, $l, MS) {
            $r.go = function (path) {
                $l.path(path);
            };
            $r.template = G.template;

            MS.register({ url: '/empresas', label: 'Empresas'});
        }])
    ;
})();
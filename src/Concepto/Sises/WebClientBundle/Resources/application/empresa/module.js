/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    G.modules.EMPRESA = 'EMPRESA';

    angular.module(G.modules.EMPRESA, ['ngRoute' ,'ngResource'])
        .config(['$routeProvider', function ($routeProvider) {
            $routeProvider
                .when('/empresas', {
                    controller: 'EmpresaListadoController',
                    templateUrl: G.template('listado_empresa')
                })
                .when('/empresas/nueva', {
                    controller: 'EmpresaNuevaController',
                    templateUrl: G.template('empresa_nueva')
                })
                .when('/empresas/:id', {
                    controller: 'EmpresaDetallesController',
                    templateUrl: G.template('empresa_ver')
                })
            ;
        }])

        .factory('Empresa', ['$resource', function($r) {
            return $r(G.json_route('/api/empresas/:id.json'), { id: '@id' }, {
                update: { method: 'PUT'}
            }, {
                stripTrailingSlashes: false
            });
        }])

        .run(['MenuService', function(MS) {
            MS.register({ name: G.modules.EMPRESA, url: '/empresas', label: 'Empresas'});
        }])
    ;
})();
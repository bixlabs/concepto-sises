/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    G.modules.EMPRESA = 'EMPRESA';

    angular.module(G.modules.EMPRESA, ['ngRoute' ,'ngResource', 'ui.router'])
        .config(['$stateProvider', function ($stateProvider) {
            $stateProvider
                .state('empresas', {
                    url: '/empresas',
                    abstract: true,
                    template: '<ui-view/>'
                })
                .state('empresas.listado', {
                    url: '',
                    controller: 'EmpresaListadoController',
                    templateUrl: G.template('empresa_listado')
                })
                .state('empresas.nueva', {
                    url: '/nueva',
                    controller: 'EmpresaNuevaController',
                    templateUrl: G.template('empresa_nueva')
                })
                .state('empresas.detalles', {
                    url: '/:id',
                    controller: 'EmpresaDetallesController',
                    templateUrl: G.template('empresa_detalles')
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
            MS.register({
                name: G.modules.EMPRESA,
                url: 'empresas.listado',
                label: 'Empresas',
                category: 'empresas'
            });
        }])
    ;
})();
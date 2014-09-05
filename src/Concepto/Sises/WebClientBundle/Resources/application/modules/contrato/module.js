/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";
    G.modules.CONTRATO = 'CONTRATO';

    angular.module(G.modules.CONTRATO, ['ngRoute' ,'ngResource', 'ui.router'])
        .config(['$stateProvider', function ($stateProvider) {
            $stateProvider
                .state('contratos', {
                    url: '/contratos',
                    abstract: true,
                    template: '<ui-view/>'
                })
                .state('contratos.listado', {
                    url: '',
                    controller: 'ContratoListadoController',
                    templateUrl: G.template('contrato_listado')
                })
                .state('contratos.nuevo', {
                    url: '/nuevo',
                    controller: 'ContratoNuevoController',
                    templateUrl: G.template('contrato_nuevo')
                })
                .state('contratos.detalles', {
                    url: ':id',
                    controller: 'ContratoDetallesController',
                    templateUrl: G.template('contrato_detalles')
                })
            ;
        }])

        .factory('Contrato', ['$resource', function($r) {
            return $r(G.json_route('/api/contratos/:id.json'), { id: '@id' }, {
                update: { method: 'PUT'}
            }, {
                stripTrailingSlashes: false
            });
        }])

        .run(['MenuService', function(MS) {
            MS.register({ name: G.modules.CONTRATO, url: 'contratos.listado', label: 'Contratos'});
        }])
})();
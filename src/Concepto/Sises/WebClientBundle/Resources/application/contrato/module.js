/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";
    G.modules.CONTRATO = 'CONTRATO';

    angular.module(G.modules.CONTRATO, ['ngRoute' ,'ngResource'])
        .config(['$routeProvider', function ($routeProvider) {
            $routeProvider
                .when('/contratos', {
                    controller: 'ContratoListadoController',
                    templateUrl: G.template('contrato_listado')
                })
                .when('/contratos/nuevo', {
                    controller: 'ContratoNuevoController',
                    templateUrl: G.template('contrato_nuevo')
                })
                .when('/contratos/:id', {
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
            MS.register({ name: G.modules.CONTRATO, url: '/contratos', label: 'Contratos'});
        }])
})();
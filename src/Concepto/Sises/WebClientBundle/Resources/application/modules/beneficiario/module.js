/**
 * Created by julian on 10/09/14.
 */
;
(function () {
    "use strict";

    G.modules.BENEFICIARIO = 'BENEFICIARIO';

    angular.module(G.modules.BENEFICIARIO, ['ngRoute' ,'ngResource', 'ui.router'])
        .config(['$stateProvider', function ($stateProvider) {
            $stateProvider
                .state('beneficiarios', {
                    url: '/beneficiarios',
                    abstract: true,
                    template: '<ui-view/>'
                })
                .state('beneficiarios.listado', {
                    url: '',
                    controller: 'BeneficiarioListadoController',
                    templateUrl: G.template('beneficiario_listado')
                })
                .state('beneficiarios.nuevo', {
                    url: '/nuevo',
                    controller: 'BeneficiarioNuevoController',
                    templateUrl: G.template('beneficiario_nuevo')
                })
                .state('beneficiarios.detalles', {
                    url: '/:id',
                    controller: 'BeneficiarioDetallesController',
                    templateUrl: G.template('beneficiario_detalles')
                })
            ;
        }])

        .factory('Beneficiario', ['$resource', function($r) {
            return $r(G.json_route('/api/beneficiarios/:id.json'), { id: '@id' }, {
                update: { method: 'PUT'}
            }, {
                stripTrailingSlashes: false
            });
        }])

        .factory('Persona', ['$resource', function($r) {
            return $r(G.json_route('/api/personas/:id.json'), { id: '@id' }, {
                update: { method: 'PUT'}
            }, {
                stripTrailingSlashes: false
            });
        }])

        .run(['MenuService', function(MS) {
            MS.register({ name: G.modules.BENEFICIARIO, url: 'beneficiarios.listado', label: 'Beneficiarios'});
        }])
    ;
})();
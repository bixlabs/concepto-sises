/**
 * Created by julian on 11/09/14.
 */
;
(function () {
    "use strict";

    // Controllers

    function Common(scope, Factory, $r, propertyName, propertiesName) {
        var id = G.guid();

        scope[propertiesName] = Factory.query();

        scope[propertyName + '_element'] = {};
        scope[propertyName + '_errors'] = {};
        scope[propertyName + '_handler'] = {
            id: id,
            actions: {
                ok: {
                    label: 'Agregar',
                    style: 'primary'
                },
                cancel: {
                    dismiss: true,
                    label: 'Volver'
                }
            }
        };

        $r.$on('modalize.action.' + id, function(event, data) {
            switch (data) {
                case 'ok':
                    scope[propertyName + '_element']['$save'](function(data, headers) {
                        scope[propertiesName] = Factory.query();
                        scope.element[propertyName] = G.extractGuid(headers('Location'));
                        scope[propertyName + '_handler'].hide();
                    }, function(response) {
                        switch (response.data.code) {
                            case 400:
                                scope[propertyName + '_errors'] = response.data.errors.children;
                                break;
                            default:
                                console.error(response);
                                break;
                        }
                    });
                    break;
            }
        });

        scope[propertyName + '_add'] = function() {
            scope[propertyName + '_element'] = new Factory();
            scope[propertyName + '_handler'].show();
        };
    }

    /**
     * BeneficiarioNuevoController
     */
    function BeneficiarioNuevoController(scope, RR) {
        G.Base.NewController.call(this, scope, RR.beneficiario);

        scope.list = function() {
            scope.go('beneficiarios.listado');
        };

        scope.detailsLocation = function(location) {
            scope.go('beneficiarios.detalles', {id: G.extractGuid(location)});
        };
    }

    /**
     * BeneficiarioListadoController
     *
     * @param scope
     * @param ResourceFactory
     * @constructor
     */
    function BeneficiarioListadoController(scope, ResourceFactory) {
        G.Base.ListController.call(this, scope, ResourceFactory.beneficiario);

        scope.details = function (id) {
            scope.go('beneficiarios.detalles', {id: id});
        };

        scope.add = function() {
            scope.go('beneficiarios.nuevo');
        };
    }

    /**
     * BeneficiarioDetallesController
     *
     * @param scope
     * @param ResourceFactory
     * @param $r
     * @constructor
     */
    function BeneficiarioDetallesController(scope, ResourceFactory, $r) {
        G.Base.UpdateController.call(this, scope, ResourceFactory.beneficiario);

        scope.list = function() {
            scope.go('beneficiarios.listado');
        };

        scope.detailsLocation = function(location) {
            scope.refresh('beneficiarios.detalles', {id: G.extractGuid(location)});
        };
    }

    G.modules.BENEFICIARIO = 'BENEFICIARIO';

    angular.module(G.modules.BENEFICIARIO, ['ngRoute' ,'ngResource', 'ui.router', 'RESOURCE'])
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

        /**
         * Register like angular.js controllers
         */
        .controller('BeneficiarioListadoController', [
            '$scope',
            'RestResources',
            BeneficiarioListadoController])
        .controller('BeneficiarioNuevoController', [
            '$scope',
            'RestResources',
            BeneficiarioNuevoController])

        .controller('BeneficiarioDetallesController', [
            '$scope',
            '$rootScope',
            'RestResources',
            BeneficiarioDetallesController])

        .run(['MenuService', function(MS) {
            MS.register({ name: G.modules.BENEFICIARIO, url: 'beneficiarios.listado', label: 'Beneficiarios'});
        }])
})();
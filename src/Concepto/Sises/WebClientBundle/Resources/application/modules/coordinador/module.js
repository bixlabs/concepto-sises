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
    G.modules.COORDINADOR = 'COORDINADOR';

    angular.module(G.modules.COORDINADOR, ['ngResource', 'ui.router'])
        .config(['$stateProvider', function($stateProvider) {
            $stateProvider
                .state('coordinador', {
                    url: '/coordinador',
                    abstract: true,
                    template: '<ui-view/>'
                })
                .state('coordinador.listado', {
                    url: '',
                    controller: 'CoordinadorListController',
                    templateUrl: G.template('coordinador/list')
                })
                .state('coordinador.nuevo', {
                    url: '/nuevo',
                    controller: 'CoordinadorNewController',
                    templateUrl: G.template('coordinador/new')
                })
                .state('coordinador.detalles', {
                    url: '/:id',
                    controller: 'CoordinadorUpdateController',
                    templateUrl: G.template('coordinador/update')
                })
            ;
        }])

        .run(['MenuService', function(MS) {
            MS.register({ name: G.modules.COORDINADOR, url: 'coordinador.listado', label: 'Coordinadores'});
        }])

        .controller('CoordinadorListController', ['RestResources', '$scope', function(RR, scope) {
            G.Base.ListController.call(this, scope, RR.coordinador);
        }])
        .controller('CoordinadorNewController', ['RestResources', '$scope', function(RR, scope) {
            G.Base.NewController.call(this, scope, RR.coordinador);
            scope.contratos = RR.contrato.query();
        }])
        .controller('CoordinadorUpdateController', ['RestResources', '$scope', function(RR, scope) {
            G.Base.UpdateController.call(this, scope, RR.coordinador);
            scope.contratos = RR.contrato.query();
        }])
})();
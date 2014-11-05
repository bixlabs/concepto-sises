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
    G.modules.RRHH = 'RRHH';

    angular.module(G.modules.RRHH, ['ngResource', 'ui.router'])
        .config(['$stateProvider', function($stateProvider) {
            $stateProvider
                .state('rrhh', {
                    url: '/personal',
                    abstract: true,
                    template: '<ui-view/>'
                })
                .state('rrhh.listado', {
                    url: '',
                    controller: 'RRHHListController',
                    templateUrl: G.template('rrhh/list')
                })
                .state('rrhh.nuevo', {
                    url: '/nuevo',
                    controller: 'RRHHNewController',
                    templateUrl: G.template('rrhh/new')
                })
                .state('rrhh.detalles', {
                    url: '/:id',
                    controller: 'RRHHUpdateController',
                    templateUrl: G.template('rrhh/update')
                })
            ;
        }])

        .run(['MenuService', function(MS) {
            MS.register({ name: G.modules.RRHH, url: 'rrhh.listado', label: 'RRHH'});
        }])

        .controller('RRHHListController', ['RestResources', '$scope', function(RR, scope) {
            G.Base.ListController.call(this, scope, RR.recurso_humano);

            scope.details = function (id) {
                scope.go('^.detalles', {id: id});
            };

            scope.add = function() {
                scope.go('^.nuevo');
            };
        }])
        .controller('RRHHNewController', ['RestResources', '$scope', function(RR, scope) {
            G.Base.NewController.call(this, scope, RR.recurso_humano);

            scope.cargos = RR.cargo_operativo.query();
            scope.contratos = RR.contrato.query();

            scope.list = function() {
                scope.go('^.listado');
            };

            scope.detailsLocation = function(location) {
                scope.go('^.detalles', {id: G.extractGuid(location)});
            };
        }])
        .controller('RRHHUpdateController', ['RestResources', '$scope', function(RR, scope) {
            G.Base.UpdateController.call(this, scope, RR.recurso_humano);

            scope.cargos = RR.cargo_operativo.query();
            scope.contratos = RR.contrato.query();

            scope.list = function() {
                scope.go('^.listado');
            };

            scope.detailsLocation = function(location) {
                scope.go('^.detalles', {id: G.extractGuid(location)});
            };
        }])
    ;
})();
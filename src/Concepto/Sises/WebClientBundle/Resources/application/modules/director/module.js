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
    G.modules.DIRECTOR = 'DIRECTOR';

    angular.module(G.modules.DIRECTOR, ['ngResource', 'ui.router'])
        .config(['$stateProvider', function($stateProvider) {
            $stateProvider
                .state('director', {
                    url: '/director',
                    abstract: true,
                    template: '<ui-view/>'
                })
                .state('director.listado', {
                    url: '',
                    controller: 'DirectorListController',
                    templateUrl: G.template('director/list')
                })
                .state('director.nuevo', {
                    url: '/nuevo',
                    controller: 'DirectorNewController',
                    templateUrl: G.template('director/new')
                })
                .state('director.detalles', {
                    url: '/:id',
                    controller: 'DirectorUpdateController',
                    templateUrl: G.template('director/update')
                })
            ;
        }])

        .run(['MenuService', function(MS) {
            MS.register({
                name: G.modules.DIRECTOR,
                url: 'director.listado',
                label: 'Directores',
                category: 'empresas'
            });
        }])

        .controller('DirectorListController', ['RestResources', '$scope', function(RR, scope) {
            G.Base.ListController.call(this, scope, RR.director);
        }])
        .controller('DirectorNewController', ['RestResources', '$scope', function(RR, scope) {
            G.Base.NewController.call(this, scope, RR.director);
        }])
        .controller('DirectorUpdateController', ['RestResources', '$scope', function(RR, scope) {
            G.Base.UpdateController.call(this, scope, RR.director);
        }])
})();
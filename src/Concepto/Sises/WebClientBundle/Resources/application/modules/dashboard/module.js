/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    G.modules.DASHBOARD = 'DASHBOARD';

    angular.module(G.modules.DASHBOARD, ['ngRoute' ,'ngResource', 'ui.router'])
        .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
            $stateProvider
                .state('dashboard', {
                    url : '/dashboard',
                    controller: 'DashboardController',
                    templateUrl: G.template('dashboard/info')
                });

            $urlRouterProvider.when('/', '/dashboard');
        }])

        .run(['MenuService', function(MS) {
            MS.register({
                name: G.modules.DASHBOARD,
                url: 'dashboard',
                label: 'Cuadro de mando',
                priority: 0
            });
        }])

        .controller('DashboardController', function() {

        })
    ;
})();
/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    G.modules.DASHBOARD = 'DASHBOARD';

    angular.module(G.modules.DASHBOARD, ['ngRoute' ,'ngResource'])
        .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
            $stateProvider
                .state('dashboard', {
                    url : '/dashboard',
                    controller: 'DashboardController',
                    template: ''
                });
            $urlRouterProvider
                .otherwise('/dashboard');
        }])
    ;
})();
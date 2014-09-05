/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    G.modules.DASHBOARD = 'DASHBOARD';

    angular.module(G.modules.DASHBOARD, ['ngRoute' ,'ngResource'])
        .config(['$routeProvider', function ($routeProvider) {
            $routeProvider
                .when('/dashboard', {
                    controller: 'DashboardController',
                    template: ''
                })
            ;
        }])
    ;
})();
/**
 * Created by julian on 4/09/14.
 */
(function() {
    "use strict";

    angular.module(G.APP, ['EMPRESA', 'DASHBOARD', 'CONTRATO', 'ui.router'])
        .config(['$urlRouterProvider', function ($urlRouterProvider) {
            $urlRouterProvider
                .otherwise('/dashboard')
            ;
        }])
        .run(['$rootScope', '$state', function ($r, $state) {
            $r.go = $state.go;
            $r.template = G.template;
        }])
    ;
})();
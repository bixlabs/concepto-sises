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
        .run(['$rootScope', '$state', '$stateParams', 'modalService', function ($r, $state, $sP, mS) {
            $r.go = $state.go;
            $r.template = G.template;
            $r.routeParams = $sP;
            $r.modal = mS;
        }])
    ;
})();
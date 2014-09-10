/**
 * Created by julian on 4/09/14.
 */
(function() {
    "use strict";

    angular.module(G.APP, ['EMPRESA', 'DASHBOARD', 'CONTRATO', 'BENEFICIARIO', 'ui.router', 'localytics.directives'])
        .config(['$urlRouterProvider', function ($urlRouterProvider) {
            $urlRouterProvider
                .otherwise('/dashboard')
            ;
        }])
        .run(['$rootScope', '$state', '$stateParams', 'modalService', function ($r, $state, $sP, mS) {
            $r.go = $state.go;
            $r.refresh = function(state, params) {
                $state.go(state, params, {
                    reload: true,
                    inherit: false,
                    notify: true
                })
            };
            $r.template = G.template;
            $r.routeParams = $sP;
            $r.modal = mS;
        }])
    ;
})();
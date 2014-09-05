/**
 * Created by julian on 4/09/14.
 */
(function() {
    "use strict";

    angular.module(G.APP, ['EMPRESA', 'DASHBOARD', 'CONTRATO'])
        .config(['$routeProvider', function ($routeProvider) {
            $routeProvider
                .otherwise({redirectTo: '/dashboard'})
            ;
        }])
        .run(['$rootScope', '$location', function ($r, $l) {
            $r.go = function (path) {
                $l.path(path);
            };

            $r.template = G.template;
        }])
    ;
})();
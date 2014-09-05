/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    angular.module(G.APP)
    /**
     * menu.entry.add
     */
        .directive('sisesMenu', ['$rootScope', '$location', function($r, $l) {
            return {
                restrict: 'A',
                replace: true,
                scope: true,
                templateUrl: G.template('menu'),
                link: function(scope) {
                    scope.entries = $r.menu_entries;
                    scope.isActive = function (viewLocation) {
                        return viewLocation === $l.path();
                    };
                }
            };
        }])
    ;
})();
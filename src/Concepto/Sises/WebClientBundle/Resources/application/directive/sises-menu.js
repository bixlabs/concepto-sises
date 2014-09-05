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
        .directive('sisesMenu', ['$rootScope', '$state', function($r, $state) {
            return {
                restrict: 'A',
                replace: true,
                scope: true,
                templateUrl: G.template('menu'),
                link: function(scope) {
                    scope.entries = $r.menu_entries;
                    scope.isActive = function (viewLocation) {
                        return $state.includes(viewLocation.split('.')[0]);
                    };
                }
            };
        }])
    ;
})();
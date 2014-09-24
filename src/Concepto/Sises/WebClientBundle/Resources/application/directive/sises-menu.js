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

                    // If module PROFILE isn't present always show menu
                    if (angular.module(G.APP).requires.indexOf(G.modules.PROFILE) === -1) {
                        $r.authState = true;
                    }

                    scope.entries = $r.menu_entries;
                    scope.isActive = function (viewLocation) {
                        return $state.includes(viewLocation.split('.')[0]);
                    };
                }
            };
        }])
    ;
})();
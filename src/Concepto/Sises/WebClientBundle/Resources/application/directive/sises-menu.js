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

                    scope.getEntries = function() {
                        return $r.menu_entries.concat($r.menu_categories);
                    };

                    //scope.entries = $r.menu_entries;
                    scope.isActive = function (viewLocation) {
                        if (typeof viewLocation === 'undefined') {
                            return false;
                        }

                        return $state.includes(viewLocation.split('.')[0]);
                    };
                }
            };
        }])

        .directive('sisesMenuItem', ['$state', '$rootScope', function($state, $r) {
            return {
                restrict: 'A',
                replace: true,
                templateUrl: G.template('menu_item'),
                scope: {
                    entry: '=sisesMenuItem'
                },
                link: function(scope) {
                    scope.onClick = function() {

                        if (typeof scope.entry.url === 'undefined') {
                            return;
                        }

                        $state.go(scope.entry.url);
                    };

                    scope.getEntries = function() {
                        return scope.entry.is_category ? $r.items_in[scope.entry.name] : [];
                    }
                }
            };
        }])
    ;
})();
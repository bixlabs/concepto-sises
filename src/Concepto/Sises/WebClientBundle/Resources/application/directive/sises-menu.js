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
                    scope.entries = [];
                    scope.isActive = function (viewLocation) {
                        return viewLocation === $l.path();
                    };

                    $r.$on('menu.entry.add', function(event, data) {
                        scope.entries.push(data)
                    });
                }
            };
        }])
    ;
})();
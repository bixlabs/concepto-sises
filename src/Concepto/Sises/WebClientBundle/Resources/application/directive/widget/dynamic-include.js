/**
 * Created by julian on 11/09/14.
 */
;
(function () {
    "use strict";


    angular.module(G.APP)
        .directive('sisesInclude', ['$http', '$templateCache', '$compile',
            function($http, $templateCache, $compile) {
                return {
                    restrict: 'A',
                    replace: true,
                    link: function(scope, el, attrs) {
                        $http.get(G.template(attrs.sisesInclude), {cache: $templateCache})
                            .success(function(tplContent) {
                                el.replaceWith($compile(tplContent)(scope));
                            });
                    }
                };
            }]);
})();
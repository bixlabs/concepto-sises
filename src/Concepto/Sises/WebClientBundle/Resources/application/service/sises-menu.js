/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    angular.module(G.APP)
        .service('MenuService', ['$rootScope', function($r) {
            var register_entry = function(params) {
                $r.$emit('menu.entry.add', params);
            };

            return {
                register: function(params) {
                    register_entry(params);
                }
            };
        }])
    ;
})();
/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    angular.module(G.APP)
        .service('MenuService', ['$rootScope', function($r) {
            $r.menu_entries = {};

            var register_entry = function(params) {
                if (!$r.menu_entries[params.name]) {
                    $r.menu_entries[params.name] = params;
                }
            };

            return {
                register: function(params) {
                    register_entry(params);
                }
            };
        }])
    ;
})();
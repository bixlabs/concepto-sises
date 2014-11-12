/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    angular.module(G.APP)
        .service('MenuService', ['$rootScope', function($r) {
            $r.menu_entries = {};
            $r.menu_categories = {};
            $r.items_in = {};

            var register_entry = function(params) {
                /** @namespace params.is_category */
                if (!$r.menu_entries[params.name]) {
                    if (params.is_category) {
                        $r.menu_categories[params.name] = params;
                    } else if (params.category) {
                        if (!$r.items_in[params.category]) {
                            $r.items_in[params.category] = [];
                        }
                        $r.items_in[params.category].push(params);

                    } else {
                        $r.menu_entries[params.name] = params;
                    }
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
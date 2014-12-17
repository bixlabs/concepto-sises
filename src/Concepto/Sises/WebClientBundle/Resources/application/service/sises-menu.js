/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    angular.module(G.APP)
        .service('MenuService', ['$rootScope', function($r) {
            $r.menu_entries = [];
            $r.menu_categories = [];
            $r.items_in = {};
            $r.menu_ordered = 'priority';
            $r.menu_reversed = false;

            var register_entry = function(params) {

                if (typeof params.priority === 'undefined') {
                    console.log("Item", params.name, "default priority", 999);
                    params.priority = 999;
                }

                if (typeof params.is_category === 'undefined') {
                    params.is_category = false;
                }

                if (params.is_category) {
                    $r.menu_categories.push(params);
                } else if (params.category) {
                    if (!$r.items_in[params.category]) {
                        $r.items_in[params.category] = [];
                    }
                    $r.items_in[params.category].push(params);

                } else {
                    $r.menu_entries.push(params);
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
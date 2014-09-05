;(function() {
    "use strict";

    var json_route = function (name) {
        return Routing.getBaseUrl() + (name);
    };

    var template = function (name) {
        return Routing.generate('view_partials', {name: name});
    };

    window.G = window.G || {
        APP: 'sises',
        template: template,
        json_route: json_route,
        modules: {}
    };
})();
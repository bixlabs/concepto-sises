;(function() {
    "use strict";

    var json_route = function (name) {
        return Routing.getBaseUrl() + (name);
    };

    var template = function (name) {
        return Routing.generate('view_partials', {name: name});
    };

    var guid = (function() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }
        return function() {
            return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
                s4() + '-' + s4() + s4() + s4();
        };
    })();

    window.G = window.G || {
        APP: 'sises',
        template: template,
        json_route: json_route,
        modules: {},
        guid: guid
    };
})();
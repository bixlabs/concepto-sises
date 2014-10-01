;(function() {
    "use strict";

    var json_route = function (name) {
        return Routing.getBaseUrl() + (name);
    };

    var route = function(name, args) {
        var arg_params = args || {};

        return Routing.generate(name, arg_params);
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

    var extractGuid = function(string) {
        var pattern = /([\w+]{8}-[\w+]{4}-[\w+]{4}-[\w+]{4}-[\w+]{12})/,
            matches = [];

        matches = string.match(pattern);

        if (matches.length == 0) {
            throw "'" + string + "' not contains a guid";
        }

        return matches[0];
    };

    window.G = window.G || {
        APP: 'sises',
        template: template,
        json_route: json_route,
        route: route,
        modules: {},
        guid: guid,
        extractGuid: extractGuid,
        views: {
            LIST: 'list',
            FORM: 'form',
            NEW: 'new',
            UPDATE: 'updates'
        }
    };
})();
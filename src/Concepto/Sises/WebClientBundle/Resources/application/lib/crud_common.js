/**
 * Created by julian on 22/09/14.
 */
;
(function () {
    "use strict";


    G.CrudCommonStates = {
        LIST: 'list',
        NEW: 'new',
        UPDATE: 'update'
    };
    /**
     *
     * @param scope Current scope
     * @param RestResources Holds rest resource configuracion
     * @constructor
     */
    var CrudCommon = function(scope, RestResources) {

        var id = G.guid();

        // Bind template method, directive has no access to $rootScope
        scope.template = scope.template || G.template;

        // Errors from forms
        scope.errors = {};

        // Can I clic button?
        scope.canSave = true;
        scope.canRemove = true;

        scope.tt = {
            currentView: G.CrudCommonStates.LIST,
            handler: {
                id: id,
                actions: {
                    cancel: {
                        label: 'Volver',
                        dismiss: true
                    }
                }
            },
            filter: {
                values: [],
                value: null,
                current: null
            },
            element: {},
            pager: {
                current: 1,
                last: 1,
                count: 0,
                limit: 10
            }
        };

        scope.edit = function(element) {
            scope.element = RestResources[scope.property].get({id: element.id});
            scope.tt.logic = G.CrudCommonStates.UPDATE;
        };

        var getPager = function(data, headers) {

            var vars = {
                current: 'X-Current-Page',
                last: 'X-Total-Pages',
                count: 'X-Total-Count',
                limit: 'X-Per-Page'
            };

            angular.forEach(vars, function(header, conf) {
                scope.pager[conf] = parseInt(headers(header));
            });
        };

        scope.changeFilter = function(filter) {
            scope.tt.filter.current = filter;
        };

        scope.clearFilter = function() {
            scope.tt.filter = {
                values: [],
                value: null,
                current: null
            };
            scope.query();
        };

        scope.query = function(page) {
            var query_params = page ? {page: page} : {};

            if (scope.tt.filter.value && scope.tt.filter.current) {
                query_params[scope.tt.filter.current.value] =
                    scope.tt.filter.current.comp + ',' + scope.tt.filter.value;
            }

            scope.elements = RestResources[scope.property].query(query_params, getPager);
        };

        scope.previousPage = function() {
            if (scope.pager.current && scope.pager.current > 1) {
                scope.query(scope.pager.current - 1);
            }
        };

        scope.showing = function() {
            var length,
                offset = ((scope.pager.current - 1) * scope.pager.limit);

            if (scope.pager.current === scope.pager.last) {
                length = scope.pager.count;
            } else {
                length = offset + scope.pager.limit;
            }

            return (1 + offset) + " - " + length + ' de ' + scope.pager.count;
        };

        scope.nextPage = function() {
            if (scope.pager.current < scope.pager.last) {
                scope.query(scope.pager.current + 1);
            }
        };

        var saveFail = function(response) {
            switch (response.data.code) {
                case 400:
                    scope.errors = response.data.errors.children;
                    break;
                default:
                    console.error(response);
                    break;
            }
            scope.canSave = true;
        };

        scope.update = function() {
            scope.canSave = false;
            scope.element['$update'](function() { scope.list(); }, saveFail);
        };

        scope.save = function() {
            scope.canSave = false;
            scope.element['$save'](function() { scope.list(); }, saveFail);
        };

        scope.list = function() {
            scope.query();
            scope.logic = G.CrudCommonStates.LIST;
        };

        scope.add = function() {
            scope.element = new RestResources[scope.property]();
            scope.logic = G.CrudCommonStates.NEW;
        };

        scope.hasError = function(name) {
            return scope.errors[name]
            && angular.isObject(scope.errors[name])
            && scope.errors[name].errors
            && scope.errors[name].errors.length;
        };
    };
})();
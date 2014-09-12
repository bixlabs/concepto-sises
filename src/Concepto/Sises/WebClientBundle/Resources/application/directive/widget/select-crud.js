/**
 * Created by julian on 11/09/14.
 */
;
(function () {
    "use strict";

    angular.module(G.APP)
        .directive('selectCrud', ['RestResources', 'FilterResources', function(RR, FR) {
            return {
                restrict: 'A',
                scope: {
                    selectCrud: '@',
                    model: '=',
                    label: '@',
                    showProperty: '@',
                    errors: '='
                },
                templateUrl: G.template('directive_select_crud'),
                link: function(scope) {
                    scope.id = G.guid();
                    scope.logic = 'list';
                    scope.handler = {
                        id: scope.id,
                        actions: {
                            cancel: {
                                label: 'Volver',
                                dismiss: true
                            }
                        }
                    };

                    scope.filters = FR[scope.selectCrud];
                    scope.filter_value = '';
                    scope._filter = {};
                    scope.errors = {};
                    scope.element = {};
                    scope.pager = {
                        current: 1,
                        last: 1,
                        count: 0,
                        limit: 10
                    };

                    scope.template = G.template;

                    scope.open = function() {
                        scope.list();
                        scope.handler.show();
                    };

                    scope.select = function(element) {
                        scope.model = element.id;
                        scope.selectedElement = element[scope.showProperty];
                        scope.handler.hide();
                    };

                    scope.edit = function(element) {
                        scope.element = RR[scope.selectCrud].get({id: element.id});
                        scope.logic = 'new';
                    };

                    var getPager = function(data, headers) {
                        scope.pager = {
                            current: parseInt(headers('X-Current-Page')),
                            last: parseInt(headers('X-Total-Pages')),
                            count: parseInt(headers('X-Total-Count')),
                            limit: parseInt(headers('X-Per-Page'))
                        };
                    };

                    scope.changeFiler = function(filter) {
                        scope._filter = filter;
                    };

                    scope.query = function(page) {
                        var query_params = page ? {page: page} : {};

                        if (scope._filter.value && scope.filter_value) {
                            query_params[scope._filter.value] = scope._filter.comp + ',' + scope.filter_value;
                        }

                        scope.elements = RR[scope.selectCrud].query(query_params, getPager);
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
                        scope.element['$update'](function() {
                            scope.list();
                        }, saveFail);
                    };

                    scope.save = function() {
                        scope.canSave = false;
                        scope.element.$save(function() {
                            scope.list();
                        }, saveFail);
                    };

                    scope.list = function() {
                        scope.query();
                        scope.logic = 'list';
                    };

                    scope.add = function() {
                        scope.element = new RR[scope.selectCrud]();
                        scope.logic = 'new';
                        //TODO: Show newAction
                        //TODO: Allow append an item to list
                        //TODO: Allow append item to list and select an item
                    };
                    scope.remove = function() {
                        //TODO: Allow remove a item from list
                    };

                    scope.hasError = function(name) {
                        return scope.errors[name]
                            && angular.isObject(scope.errors[name])
                            && scope.errors[name].errors
                            && scope.errors[name].errors.length;
                    };
                }
            }
        }])
})();
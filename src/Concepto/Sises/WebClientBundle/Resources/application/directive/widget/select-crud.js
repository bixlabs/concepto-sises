/**
 * Created by julian on 11/09/14.
 */
;
(function () {
    "use strict";

    angular.module(G.APP)
        .directive('selectCrud', ['RestResources', function(RR) {
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

                    scope.element = {};

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

                    scope.list = function() {
                        scope.elements = RR[scope.selectCrud].query();
                        scope.logic = 'list';
                    };
                    scope.add = function() {
                        scope.logic = 'new';
                        //TODO: Show newAction
                        //TODO: Allow append an item to list
                        //TODO: Allow append item to list and select an item
                    };
                    scope.remove = function() {
                        //TODO: Allow remove a item from list
                    };
                    scope.update = function() {
                        //TODO: Allow update a item content
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
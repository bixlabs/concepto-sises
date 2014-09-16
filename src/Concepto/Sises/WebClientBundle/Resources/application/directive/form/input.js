/**
 * Created by julian on 5/09/14.
 */
;
(function () {
    "use strict";

    var CrudCommon = function(scope, RR, FR) {

        scope.tt.logic = 'list';
        scope.tt.handler = {
            id: scope.id,
            actions: {
                cancel: {
                    label: 'Volver',
                    dismiss: true
                }
            }
        };

        var selected = false;

        var selectedValue = function(el) {
            var childScope = angular.extend(scope.$new(), el);
            scope.tt.selectedElement = childScope.$eval(scope.showProperty);
        };

        // Show the name selectedElement
        scope.$watch(function() {
            return scope.form.model[scope.modelProperty];
        }, function(newVal) {
            if (!selected && typeof newVal !== 'undefined') {
                var el = RR[scope.property].get({id: newVal, extra: 'list'}, function() {
                    selectedValue(el);
                });
            }
        });

        scope.tt.filters = FR[scope.property];
        scope.tt.filter_value = '';
        scope.tt._filter = {};
        scope.errors = {};
        scope.element = {};
        scope.pager = {
            current: 1,
            last: 1,
            count: 0,
            limit: 10
        };

        // Bind template method, directive has no access to $rootScope
        scope.template = G.template;


        scope.open = function() {
            selected = false;
            scope.list();
            scope.tt.handler.show();
        };

        scope.select = function(element) {
            selected = true;
            scope.form.model[scope.modelProperty] = element.id;
            selectedValue(element);
            scope.tt.handler.hide();
        };

        scope.edit = function(element) {
            scope.element = RR[scope.property].get({id: element.id});
            scope.logic = 'update';
        };

        var getPager = function(data, headers) {
            scope.pager = {
                current: parseInt(headers('X-Current-Page')),
                last: parseInt(headers('X-Total-Pages')),
                count: parseInt(headers('X-Total-Count')),
                limit: parseInt(headers('X-Per-Page'))
            };
        };

        scope.changeFilter = function(filter) {
            scope.tt._filter = filter;
        };

        scope.clearFilter = function() {
            scope.tt._filter = {};
            scope.tt.filter_value = '';
            scope.query();
        };

        scope.query = function(page) {
            var query_params = page ? {page: page} : {};

            if (scope.tt._filter.value && scope.tt.filter_value) {
                query_params[scope.tt._filter.value] =
                    scope.tt._filter.comp + ',' + scope.tt.filter_value;
            }

            scope.elements = RR[scope.property].query(query_params, getPager);
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
            scope.element['$save'](function() {
                scope.list();
            }, saveFail);
        };

        scope.list = function() {
            scope.query();
            scope.logic = 'list';
        };

        scope.add = function() {
            scope.element = new RR[scope.property]();
            scope.logic = 'new';
        };

        scope.hasError = function(name) {
            return scope.errors[name]
                && angular.isObject(scope.errors[name])
                && scope.errors[name].errors
                && scope.errors[name].errors.length;
        };
    };

    var CommonErrors = function(scope) {
        scope.hasErrors = function() {
            return scope.getErrors().length > 0;
        };

        scope.getErrors = function() {
            if (scope.form.errors
                && scope.form.errors[scope.property]
                && scope.form.errors[scope.property].errors) {
                return scope.form.errors[scope.property].errors;
            }

            return [];
        };
    };

    var inputLinkFunc = function(scope, el, attrs, controllers) {

        CommonErrors.call(this, scope);

        scope.tt = {
            id: G.guid(),
            type: 'text'
        };

        if (controllers[1]) {
            scope.form = controllers[1].scope;
        } else {
            scope.form = controllers[0].scope;
        }

        var attribs = [
            'placeholder',
            'label',
            'required',
            'type'
        ];

        // Proccess extra attributes
        angular.forEach(attribs, function(attr) {
            scope.tt[attr] = attrs[attr] ? scope.$eval(attrs[attr]) : '';
        });

        scope.isRequired = function() {
            if (scope.tt.required) {
                return scope.tt.required;
            }

            return false;
        };

        scope.open = function($event) {
            $event.preventDefault();
            $event.stopPropagation();

            scope.opened = true;
        };
    };

    angular.module(G.APP)
        .directive('sisesForm', function() {
            return {
                restrict: 'A',
                transclude: true,
                replace: true,
                template: '<form class="form-horizontal sises-form" data-ng-transclude></form>',
                scope: {
                    model: '=sisesForm',
                    errors: '='
                },
                controller: function($scope) {
                    this.scope = $scope;
                }
            };
        })
        .directive('sisesCompound', function() {
            return {
                restrict: 'A',
                transclude: true,
                replace: true,
                require: '^sisesForm',
                template: '<div data-ng-transclude></div>',
                scope: {
                    model: '=sisesCompound'
                },
                controller: function($scope) {
                    this.scope = $scope;
                }
            };
        })
        .directive('sisesFormInput', function() {
            return {
                restrict: 'A',
                replace: true,
                require: ['^sisesForm', '?^sisesCompound'],
                templateUrl: G.template('directive/form_input'),
                scope: {
                    property: '@sisesFormInput'
                },
                link: inputLinkFunc
            };
        })
        .directive('sisesFormEmail', function() {
            return {
                restrict: 'A',
                replace: true,
                require: ['^sisesForm', '?^sisesCompound'],
                templateUrl: G.template('directive/form_input'),
                scope: {
                    property: '@sisesFormEmail'
                },
                link: function(scope, el, attrs, form) {
                    inputLinkFunc.call(this, scope, el, attrs, form);
                    scope.tt.placeholder = 'nombre@ejemplo.com';
                    scope.tt.type = 'email';
                }
            }
        })

        .directive('sisesFormSelect', function() {
            return {
                restrict: 'A',
                replace: true,
                require: ['^sisesForm', '?^sisesCompound'],
                templateUrl: G.template('directive/form_select'),
                scope: {
                    property: '@sisesFormSelect',
                    options: '=',
                    optionsKey: '@',
                    optionsLabel: '@'
                },
                link: inputLinkFunc
            }
        })
        .directive('sisesFormSelectCrud', ['RestResources', 'FilterResources', function(RR, FR) {
            return {
                restrict: 'A',
                require: ['^sisesForm', '?^sisesCompound'],
                templateUrl: G.template('directive/form_select_crud'),
                scope: {
                    modelProperty: '@sisesFormSelectCrud',
                    property: '@crud',
                    showProperty: '@'
                },
                 link: function(scope, el, attrs, controllers) {
                     inputLinkFunc.call(this, scope, el, attrs, controllers);
                     CrudCommon.call(this, scope, RR, FR);
                     if (!scope.readOnly) {
                         scope.readOnly = false;
                     }
                 }
            }
        }])
        .directive('sisesFormImage', function($timeout) {
            return {
                restrict: 'A',
                replace: true,
                require: ['^sisesForm', '?^sisesCompound'],
                templateUrl: G.template('directive/form_image'),
                scope: {
                    property: '@sisesFormImage'
                },
                link: function(scope, el, attrs, form) {
                    inputLinkFunc.call(this, scope, el, attrs, form);
                    scope.uploader = {};
                    scope.tt.active = false;
                    scope.tt.percent = 0;

                    scope.$watch('form.model.' + scope.property, function(val) {
                        updateImage(val);
                    });

                    var defaultImage = '/bundles/siseswebclient/images/logo-default.png';

                    var updateImage = function(img) {
                        scope.tt.logo = img ? ('/uploads/documentable/' + img ): defaultImage;
                    };

                    scope.fileUploaded = function(res) {
                        scope.form.model[scope.property] = JSON.parse(res.response).file;

                        $timeout(function() {
                            scope.tt.active = false;
                            scope.tt.percent = 0;
                        }, 100);
                    };

                    scope.fileAdded = function() {
                        scope.tt.percent = 0;
                        scope.tt.active = true;
                    };

                    if (!attrs.buttonLabel) {
                        scope.tt.buttonLabel = 'Subir imagen';
                    }
                }
            }
        })
})();
/**
 * Copyright © 2014 Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 *
 * This file is part of concepto-sises.
 *
 * concepto-sises
 * can not be copied and/or distributed without the express
 * permission of Julian Reyes Escrigas <julian.reyes.escrigas@gmail.com>
 */
;
(function () {
    "use strict";


    angular.module(G.APP)
    /**
     * directive sisesFormSelect
     */
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
                link: function(scope, el ,attrs, controllers) {
                    G.Form.InputFormLink.call(this, scope, el, attrs, controllers);
                }
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
                    showProperty: '@',
                    parentId: '='
                },
                link: function(scope, el ,attrs, controllers) {

                    var selectedValue,
                        getHandler,
                        queryList,
                        getFilter,
                        buildFilterParams,
                        extractPager,
                        selected = false;

                    G.Form.InputFormLink.call(this, scope, el, attrs, controllers);

                    selectedValue = function(el) {
                        var isolateScope = angular.extend(scope.$new(), el);
                        scope.formProperties.selectedElement =
                            isolateScope.$eval(scope.showProperty);
                    };

                    // Permite cambiar el valor actual
                    scope.changeSelectedValue = function (el) {
                        selected = true;
                        scope.form.model[scope.modelProperty] = el.id;
                        selectedValue(el);
                        getHandler().hide();
                    };

                    // Determina si debe actualizar el valor del input
                    scope.$watch('form.model.' + scope.modelProperty, function(val) {
                        if (!(!selected && typeof val !== 'undefined')) {
                            return;
                        }

                        var el = RR[scope.property].get({id: val, extra: 'list'}, function() {
                            selectedValue(el);
                        });
                    });

                    // No muestra el boton agregar
                    scope.formProperties.readOnly = scope.formProperties.readOnly || false;

                    // Vistas
                    scope.formProperties.view = G.views.LIST;

                    // Define las acciones del dialogo
                    scope.formProperties.handler = {
                        id: scope.id,
                        actions: {
                            cancel: {
                                label: 'Volver',
                                dismiss: true
                            }
                        }
                    };

                    // Devuelve el handler del dialogo principal
                    getHandler = function() {
                        return scope.formProperties.handler;
                    };

                    // Agrega los filtros como parametros
                    buildFilterParams = function(query_params) {
                        if (!angular.equals({}, getFilter().current)
                            && !angular.empty(getFilter().value)) {
                            query_params[getFilter.current.value] =
                                getFilter.current.comp + ', ' + getFilter.value;
                        }

                        if (scope.parentId) {
                            query_params['parent'] = scope.parentId;
                        }

                        return query_params;
                    };

                    // Obtiene los parametros de paginacion
                    extractPager = function(data, headers) {
                        var pager = {
                            current: parseInt(headers('X-Current-Page')),
                            last: parseInt(headers('X-Total-Pages')),
                            count: parseInt(headers('X-Total-Count')),
                            limit: parseInt(headers('X-Per-Page'))
                        };

                        angular.forEach(pager, function(value, index){
                            scope.formProperties.pager[index] = value;
                        });

                    };

                    // Obtiene el listado de elementos
                    queryList = function(page) {
                        var query_params = page ? {page: page} : {};

                        buildFilterParams(query_params);

                        scope.elements = RR[scope.property].query(query_params, extractPager);

                    };

                    scope.queryList = queryList;

                    // Filtro por defecto
                    scope.formProperties.filter = {
                        value: '',
                        current: {},
                        filters: FR[scope.property]
                    };

                    getFilter = function() {
                        return scope.formProperties.filter;
                    };

                    scope.setFilter = function(filter) {
                        getFilter().current = filter;
                    };

                    scope.clearFllter = function() {
                        getFilter().filter = {};
                        getFilter().value = '';

                        queryList();
                    };

                    // Paginador por defecto
                    scope.formProperties.pager = {
                        current: 1,
                        last: 1,
                        count: 0,
                        limit: 10,
                        nextPage: function() {
                            if (this.current < this.last) {
                                queryList(++this.current);
                            }
                        },
                        previousPage: function() {
                            if (this.current && this.current > 1) {
                                queryList(--this.current);
                            }
                        },
                        showingMessage: function() {
                            var length,
                                offset = ((this.current - 1) * this.limit);

                            length =
                                this.current === this.last ? this.count : offset + this.limit;

                            return (1 + offset) + " - " + length + ' de ' + scope.pager.count;
                        }
                    };

                    // Muestra el listado de registros
                    scope.showList = function() {
                        queryList();
                        scope.formProperties.view = G.views.LIST;
                    };

                    //Muestra el formulario para nuevo elemento
                    scope.newElement = function() {
                        scope.element = new RR[scope.property]();
                        scope.formProperties.view = G.views.NEW;
                    };

                    scope.editElement = function(element) {
                        scope.element = RR[scope.property].get({id: element.id});
                        scope.formProperties.view = G.views.UPDATE;
                    };

                    scope.saveElement = function() {
                        var method =
                            scope.formProperties.view === G.views.UPDATE ? '$update' : '$save';

                        scope.element[method](function() {
                            scope.showList();
                        }, function(response) {
                            switch (response.data.code) {
                                case 400:
                                    scope.errors = response.data.errors.children;
                                    break;
                                default:
                                    // TODO: Mostrar un globo notificacion al usuario
                                    break;
                            }
                        });
                    };

                    // Abre el cuadro de dialogo principal
                    scope.showDialog = function() {
                        selected = false;
                        scope.showList();
                        getHandler().show();
                    };
                }
            }
        }])
    ;
})();
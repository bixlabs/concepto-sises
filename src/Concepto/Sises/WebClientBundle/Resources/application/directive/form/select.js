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
        .directive('sisesFormEmbedCollection', ['RestResources', '$rootScope', '$q', function(RR, $rootScope, $q) {
            return {
                restrict: 'A',
                require: ['^sisesForm', '?^sisesCompound'],
                templateUrl: G.template('directive/form_embed_collection'),
                transclude: true,
                replace: true,
                scope: {
                    elements: '=sisesFormEmbedCollection',
                    uniqueTest:  '=',
                    buttonLabel: '@'
                },
                link: function (scope, el, attrs, controllers) {
                    var getHandler,
                        find,
                        attrName;

                    G.Form.InputFormLink.call(this, scope, el, attrs, controllers);

                    // Encuentra y devuelve el indice del elemento en el listado
                    find = function(element) {
                        var founded = -1,
                            test, combinedTest, value,
                            i, e;

                        // Solo para arrays
                        if (angular.isArray(scope.elements)) {
                            // Se busca el elemento si cumple con la condicion de unico
                            for (e = scope.elements.length -1; e >= 0; e--) {
                                value = scope.elements[e];

                                // Debe cumplir todos los criterios estrictamente
                                combinedTest = true;
                                for (i = scope.uniqueTest.length - 1; i >= 0; i--) {
                                    test = scope.uniqueTest[i];
                                    combinedTest = (combinedTest && (element[test] === value[test]));
                                }

                                if (combinedTest) {
                                    founded = e;
                                    break;
                                }
                            }
                        }

                        return founded;
                    };

                    // Elimina un elemento del listado si lo encuentra
                    scope.removeElement = function(element, $event) {
                        $event && $event.stopPropagation();

                        var found = find(element);

                        if (found !== -1) {
                            scope.elements.splice(found, 1);
                        }
                    };

                    // Agrega un elemento al listado si no lo encuentra
                    scope.addElement = function(element, $event) {
                        $event && $event.stopPropagation();

                        var found = find(element);

                        if (found === -1) {
                            scope.elements = scope.elements || [];
                            scope.elements.push(element);
                        }
                    };

                    // Inicializa propiedades
                    if (!scope.formProperties.label) {
                        scope.formProperties.label = scope.buttonLabel;
                    }

                    function camelToUnder(str) {
                        return str.replace(/\W+/g, '_').replace(/([a-z\d])([A-Z])/g, '$1_$2').toLowerCase();
                    }

                    // Estructura de la tabla
                    scope.tableHeaders = [];
                    scope.tableFields = {};

                    // Transformadores de datos
                    scope.formProperties.transformedData = {};

                    var getDataTransformer = function(name) {
                        if (name) {
                            return scope.formProperties.transformedData[name];
                        }

                        return scope.formProperties.transformedData;
                    };

                    var setDataTransformer = function(name, value) {
                        scope.formProperties.transformedData[name] = value;
                    };

                    // Procesa los attributos pasados a la directiva
                    angular.forEach(attrs, function(attrValue, attrKey) {
                        // Obtiene las columnas y valores a mostrar pasados como 'table-field-*'
                        if (attrKey.match(/^tableField/)) {
                            attrName = camelToUnder(attrKey.replace('tableField', ''));
                            scope.tableHeaders.push(attrValue);
                            scope.tableFields[attrValue] = attrName;
                        }

                        // Prepara los transformadores de datos
                        if(attrKey.match(/^tableTransform/)) {
                            attrName = camelToUnder(attrKey.replace('tableTransform', ''));
                            var params = attrValue.split(',');

                            setDataTransformer(attrName, {
                                values: [],
                                resource: RR[params[0]],
                                showProperty: params[1],
                                deferred: {},
                                transformedValue: {}
                            });
                        }

                        // Prepara los transformadores dependientes
                        if (attrKey.match(/^transformParent/)) {
                            attrName = camelToUnder(attrKey.replace('transformParent', ''));

                            var transformer = getDataTransformer(attrName);
                            transformer.parentId = attrValue;
                        }
                    });

                    // Organiza los encabezados ya que son invertidos al leerlos
                    scope.tableHeaders.reverse();

                    // Devuelve el valor apropiado
                    scope.resolveValue = function(element, propertyName) {
                        var property = scope.tableFields[propertyName];
                        if (property) {
                            var transformer = getDataTransformer(property);
                            // Si hay un transformador devolverlo
                            if (transformer) {
                                var valueTotransform = element[property];
                                if (typeof transformer.transformedValue[valueTotransform] === 'undefined') {
                                    transformer.values.push(valueTotransform);
                                    transformer.transformedValue[valueTotransform] = '';
                                    transformer.deferred[valueTotransform] = $q.defer();
                                    transformer.deferred[valueTotransform].promise.then(function(data) {
                                        transformer.transformedValue[valueTotransform] = data;

                                        // Liberamos recursos
                                        delete transformer.deferred[valueTotransform];
                                    });
                                    resolveTransformedValues();
                                }

                                return transformer.transformedValue[valueTotransform];
                            }

                            return element[property];
                        } else {
                            return '';
                        }
                    };

                    // Indica cuando hay una transformacion en progreso
                    var transformInProgress = false;

                    // Resuelve las transformaciones de datos
                    var resolveTransformedValues = function() {
                        if (transformInProgress) {
                            return;
                        }
                        // Ejecuta cada transformador
                        angular.forEach(getDataTransformer(), function(transformer, key) {
                            // Si no hay valores a transformar no hacer nada
                            if (transformer.values.length > 0) {
                                // se hace copia values puede cambiar durante el proceso
                                var values = transformer.values.concat(),
                                    index,
                                    query_params = {};
                                transformer.values = [];
                                transformInProgress = true;

                                // Si hay un valor padre definido
                                if (transformer.parentId) {
                                    query_params['parent'] = scope.$parent.$eval(transformer.parentId);
                                }

                                // Se consultan en grupos si hay mas de un valor para consultar
                                query_params['uuid'] = 'A,' + values.join(';');

                                var result = transformer.resource.query(query_params, function() {
                                    // Una vez obtenido el resultado el valor no es mas necesario
                                    angular.forEach(result, function(value) {
                                        transformer.deferred[value.id].resolve(value[transformer.showProperty]);
                                        index = transformer.values.indexOf(value.id);
                                        if (index !== -1) {
                                            transformer.values.splice(index, 1);
                                        }
                                    });
                                    transformInProgress = false;
                                    // Si hay valores pendientes (agregaron asincronamente), volvemos a transformar
                                    if (transformer.values.length > 0) {
                                        resolveTransformedValues();
                                    }
                                }, function() {
                                    transformInProgress = false;
                                });
                            }
                        });
                    };

                    // Define el manejador para el dialogo
                    scope.formProperties.handler = {
                        id: scope.formProperties.id,
                        actions: {
                            ok: {
                                label: 'Guardar',
                                style: 'primary'
                            },
                            cancel: {
                                label: 'Volver',
                                dismiss: true
                            }
                        }
                    };

                    // Espera por el boton Agregar 'ok'
                    $rootScope.$on('modalize.action.' + scope.formProperties.id, function(event, data) {
                        switch (data) {
                            case "ok":
                                scope.saveElement();
                                break;
                            default:
                                break;
                        }
                    });

                    // Devuelve el handler del dialogo principal
                    getHandler = function() {
                        return scope.formProperties.handler;
                    };

                    // Abre el cuadro de dialogo del formulario
                    scope.showDialog = function() {
                        scope.element = {};
                        getHandler().show();
                    };

                    // Agrega el elemento y cierra el dialogo del formulario
                    scope.saveElement = function() {

                        var finded = find(scope.element);

                        // Guarda
                        if (finded === -1) {
                            scope.addElement(scope.element);
                        }
                        // Actualiza
                        else {
                            scope.elements[finded] = angular.extend({}, scope.element);
                            scope.element = {};
                        }

                        getHandler().hide();
                    };

                    scope.editElement = function(element) {
                        scope.element = element;
                        getHandler().show();
                    };
                }
            };
        }])
    /**
     * directive sisesFormSelectCrud
     */
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
                        selected = false;
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
                            var emptyOb = {};
                            emptyOb[scope.showProperty] = '';
                            selectedValue(emptyOb);
                        } else {
                            var el = RR[scope.property].get(buildFilterParams({id: val, extra: 'list'}), function() {
                                selectedValue(el);
                            });
                        }
                    });

                    // No muestra el boton agregar
                    scope.formProperties.readOnly = scope.formProperties.readOnly || false;

                    // Vistas
                    scope.formProperties.view = G.views.LIST;

                    // Define las acciones del dialogo
                    scope.formProperties.handler = {
                        id: scope.formProperties.id,
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

                            return (1 + offset) + " - " + length + ' de ' + this.count;
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

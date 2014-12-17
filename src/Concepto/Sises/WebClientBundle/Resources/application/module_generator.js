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

    function controllerName(prefix) {
        var name = prefix.replace('.', '');
        return name[0].toUpperCase() + name.substr(1) + 'Controller';
    }

    function BaseController(scope) {
        var that = this;

        // Guarda los errors de formulario
        scope.errors = {};
        scope.canSave = true;
        scope.canRemove = true;

        scope.testSave = function() {
            return !scope.canSave;
        };

        scope.testRemove = function() {
            return !scope.canRemove;
        };

        scope.remove = function() {
            scope.canRemove = false;
            scope.modal.alert('Esta seguro de eliminar estos datos?', function() {
                scope.element['$delete'](function() {
                    scope.list();
                }, function () {
                    scope.canRemove = true;
                });
            }, function() {
                scope.canRemove = true;
            })
        };

        // Funciones para cambiar de controlador
        scope.list = function() { scope.go('^.list'); };

        scope.details = function (id) {
            scope.refresh('^.update', {id: G.extractGuid(id)});
        };

        scope.add = function() {
            scope.go('^.new');
        };

        scope.save = function() {
            if (typeof scope.element !== 'undefined') {
                var method = typeof scope.element.id === 'undefined' ? '$save' : '$update';
                scope.canSave = false;
                scope.element[method](that.saveSuccess, that.saveFail);
            }
        };

        that.saveSuccess = function(data, headers) {
            scope.canSave = true;
            scope.details(headers('Location'))
        };

        that.setErrors = function(errors) {
            scope.errors = errors;
        };

        that.saveFail = function(response) {
            switch (response.data.code) {
                case 400:
                    that.setErrors(response.data.errors);
                    break;
                default:
                    alert("Ha ocurrido un error " + response.data.code);
                    console.log(response.data);
                    break;
            }
            scope.canSave = true;
        };
    }

    /**
     * Devuelve un modulo de Angular
     *
     * @param name
     * @param config
     * @returns {*}
     * @constructor
     */
    function BuildModule(name, config) {
        var state, stateIdx, module, autoController = false;

        // Define el prefijo
        if (typeof config.prefix === 'undefined') {
            config.prefix = name.toLowerCase();
        }

        // Define el recurso a usar
        if (typeof config.resource === 'undefined') {
            config.resource = config.prefix;
        }

        // Controladores pordefecto
        if (typeof config.controllers === 'undefined') {
            config.controllers = {};
        }

        // Si los estados no son definidos
        if (typeof config.states === 'undefined') {
            autoController = true;
            // Estados por defecto
            config.states = [
                {suffix: '', abstract: true, template: '<ui-view/>', url: '/' + config.prefix},
                {suffix: '.list', url: '/list', name: 'list'},
                {suffix: '.new', url: '/new', name: 'new'},
                {suffix: '.update', url: '/:id', name: 'update'}
            ];
            for (stateIdx = 0; stateIdx < config.states.length; stateIdx++) {
                state = config.states[stateIdx];
                // Solo estados no abstractos
                if (typeof state.abstract === 'undefined') {
                    state.templateUrl = G.template(config.prefix + '/' + state.name);
                    state.controller = name + '.' + controllerName(state.suffix);
                }
            }
        }

        /**
         * Define el modulo
         */
        module = angular.module(name, ['ngResource', 'ui.router'])
            .config(['$stateProvider', function ($stateProvider) {
                for (stateIdx = 0; stateIdx < config.states.length; stateIdx++) {
                    state = config.states[stateIdx];
                    $stateProvider.state(config.prefix + state.suffix, state);
                }
            }])
        ;

        // Si se definen los estados por defecto se definen los controladores
        // por defecto
        if (autoController) {

            var defaultsDeps = ['RestResources', '$scope'],
                listDefaultDeps = ['FilterResources'],
                listCtrl = {},
                newCtrl = {},
                editCtrl = {};

            if (config.controllers.list) {
                listCtrl = config.controllers.list;
                listCtrl.deps =
                    listCtrl.deps
                        ? defaultsDeps.concat(listDefaultDeps).concat(listCtrl.deps)
                        : defaultsDeps.concat(listDefaultDeps);
            } else {
                listCtrl = { func: function(RR, scope, FR) {}, deps: defaultsDeps.concat(listDefaultDeps)};
            }

            if (config.controllers.edit) {
                editCtrl = config.controllers.edit;
                editCtrl.deps = editCtrl.deps ? defaultsDeps.concat(editCtrl.deps) : defaultsDeps;
            } else {
                editCtrl = { func: function(RR, scope) {}, deps: defaultsDeps};
            }

            if (config.controllers.new) {
                newCtrl = config.controllers.new;
                newCtrl.deps = newCtrl.deps ? defaultsDeps.concat(newCtrl.deps) : defaultsDeps;
            } else {
                newCtrl = { func: function(RR, scope) {}, deps: defaultsDeps};
            }

            /**
             * {name}.ListController
             */
            module.controller(name + '.ListController', listCtrl.deps.slice().concat([
                function () {
                    var scopeIdx = listCtrl.deps.indexOf('$scope'),
                        rrIdx = listCtrl.deps.indexOf('RestResources'),
                        frIdx = listCtrl.deps.indexOf('FilterResources'),
                        RR = arguments[rrIdx],
                        scope = arguments[scopeIdx],
                        FR = arguments[frIdx],
                        getFilter = function() {
                            return scope.filter;
                        },
                        queryList = function(page) {
                            var query_params = page ? {page: page} : {};

                            buildFilterParams(query_params);

                            scope.elements = RR[config.resource].query(query_params, extractPager);

                        },
                        buildFilterParams = function(query_params) {
                            if (!angular.equals({}, getFilter().current)
                                && getFilter().value) {
                                query_params[getFilter().current.value] =
                                    getFilter().current.comp + ',' + getFilter().value;
                            }

                            return query_params;
                        },
                        extractPager = function(data, headers) {
                        var pager = {
                            current: parseInt(headers('X-Current-Page')),
                            last: parseInt(headers('X-Total-Pages')),
                            count: parseInt(headers('X-Total-Count')),
                            limit: parseInt(headers('X-Per-Page'))
                        };

                        angular.forEach(pager, function(value, index){
                            scope.pager[index] = value;
                        });

                    };

                    scope.setFilter = function(filter) {
                        getFilter().current = filter;
                    };

                    scope.clearFilter = function() {
                        getFilter().filter = {};
                        getFilter().value = '';

                        queryList();
                    };

                    scope.queryList = queryList;

                    // Configuracion inicial del paginador
                    scope.pager = {
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

                    // Filtro por defecto
                    scope.filter = {
                        value: '',
                        current: {},
                        filters: FR[config.resource]
                    };

                    BaseController.call(this, scope);
                    listCtrl.func.apply(this, arguments);
                    queryList();
                }
            ]));

            /**
             * {name}.NewController
             */
            module.controller(name + '.NewController', newCtrl.deps.slice().concat([
                function () {
                    var scopeIdx = newCtrl.deps.indexOf('$scope'),
                        rrIdx = newCtrl.deps.indexOf('RestResources'),
                        RR = arguments[rrIdx],
                        scope = arguments[scopeIdx];

                    scope.element = new RR[config.resource]();
                    BaseController.call(this, scope);
                    newCtrl.func.apply(this, arguments);
                }
            ]));

            /**
             * {name}.UpdateController
             */
            module.controller(name+ '.UpdateController', editCtrl.deps.slice().concat([
                function() {

                    var scopeIdx = editCtrl.deps.indexOf('$scope'),
                        rrIdx = editCtrl.deps.indexOf('RestResources'),
                        RR = arguments[rrIdx],
                        scope = arguments[scopeIdx];

                    scope.element = RR[config.resource].get(
                        {id: scope.routeParams.id},
                        function() {}, // Ok response
                        function get_element_error(response) { // Error response
                            switch (response.status) {
                                case 404:
                                    scope.go('^.list');
                                    break;
                                default:
                                    break;
                            }
                        });
                    BaseController.call(this, scope);
                    editCtrl.func.apply(this, arguments);
                }
            ]));
        }

        /**
         * Registra en el menu principal el modulo y lo hace accesible
         */
        if (typeof config.register !== 'undefined' && config.register) {
            var defaultState =
                angular.isString(config.register) ? config.register : config.prefix + '.list';
            module.run(['MenuService', function (MS) {
                var reg = {
                    name: name,
                    url: defaultState,
                    label: config.label,
                    priority: typeof config.priority !== 'undefined' ? config.priority : 999
                };

                if (typeof config.category !== 'undefined') {
                    reg.category = config.category;
                }
                MS.register(reg);
            }])
        }
        return module;
    }
    G.BuildModule = BuildModule;
    G.Base.Controller = BaseController;
})();
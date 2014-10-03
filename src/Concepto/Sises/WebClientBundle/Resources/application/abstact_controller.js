/**
 * Created by julian on 8/09/14.
 */
;
(function () {
    "use strict";

    /**
     * ListController
     *
     * @param scope
     * @param Factory
     * @constructor
     */
    function ListController(scope, Factory) {
        scope.elements = Factory.query();

        scope.details = function (id) {
            scope.go('^.detalles', {id: id});
        };

        scope.add = function() {
            scope.go('^.nuevo');
        };
    }

    /**
     * CoreController
     *
     * @param scope
     * @constructor
     */
    function CoreController(scope) {
        var that = this;

        scope.errors = {};
        scope.canSave = true;

        scope.list = function() {
            throw " list() Not implemented!";
        };

        scope.detailsLocation = function(location) {
            throw  "detailsLocation(location) not implemented! " + location;
        };

        scope.testSave = function() {
            return !scope.canSave;
        };

        scope.save = function() {
            scope.canSave = false;
            scope.element.$save(that.saveSuccess, that.saveFail);
        };

        that.saveSuccess = function(data, headers) {
            scope.canSave = true;
            scope.detailsLocation(headers('Location'))
        };

        that.setErrors = function(errors) {
            scope.errors = errors;
        };

        that.saveFail = function(response) {
            switch (response.data.code) {
                case 400:
                    that.setErrors(response.data.errors.children);
                    break;
                default:
                    console.error(response);
                    break;
            }
            scope.canSave = true;
        };
    }

    /**
     * UpdateController
     *
     * @param scope
     * @param Factory
     * @constructor
     */
    function UpdateController(scope, Factory) {
        var that = this;
        CoreController.call(this, scope);

        scope.element = Factory.get({id: scope.routeParams.id});
        scope.canRemove = true;

        scope.testRemove = function() {
            return !scope.canRemove;
        };

        scope.remove = function() {
            scope.canRemove = false;
            scope.modal.alert('Esta seguro de eliminar estos datos?', function() {
                scope.element.$delete(function() {
                    scope.list();
                }, function (response) {
                    console.error(response);
                    scope.canRemove = true;
                });
            })
        };

        /**
         * override save function to update
         */
        scope.save = function() {
            scope.canSave = false;
            scope.element.$update(that.saveSuccess, that.saveFail);
        };

        scope.list = function() {
            scope.go('^.listado');
        };

        scope.detailsLocation = function(location) {
            scope.go('^.detalles', {id: G.extractGuid(location)});
        };
    }

    /**
     * Extends method from parent
     * @type {CoreController.prototype}
     */
    UpdateController.prototype = Object.create(CoreController.prototype);


    /**
     * NewController
     *
     * @param scope
     * @param Factory
     * @constructor
     */
    function NewController(scope, Factory) {
        CoreController.call(this, scope);
        scope.element = new Factory();

        scope.list = function() {
            scope.go('^.listado');
        };

        scope.detailsLocation = function(location) {
            scope.go('^.detalles', {id: G.extractGuid(location)});
        };
    }


    /**
     * Exports
     */
    G.Base = {
        CoreController: CoreController,
        NewController: NewController,
        ListController: ListController,
        UpdateController: UpdateController
    };
})();
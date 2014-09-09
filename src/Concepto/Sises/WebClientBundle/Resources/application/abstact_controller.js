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

        scope.details = function () {
            throw "details() Not implemented!";
        };

        scope.add = function() {
            throw "add() Not implemented!";
        }
    }

    /**
     * CoreController
     *
     * @param scope
     * @constructor
     */
    function CoreController(scope) {
        scope.errors = {};
        scope.canSave = true;

        scope.list = function() {
            throw " list() Not implemented!";
        };

        scope.testSave = function() {
            return !scope.canSave;
        };

        var that = this;

        scope.save = function() {
            scope.canSave = false;
            scope.element.$save(that.saveSuccess, that.saveFail);
        };

        this.saveSuccess = function() {
            scope.list();
        };

        this.setErrors = function(errors) {
            scope.errors = errors;
        };

        this.saveFail = function(response) {
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
        var that = this;
        scope.save = function() {
            scope.canSave = false;
            scope.element.$update(that.saveSuccess, that.saveFail);
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
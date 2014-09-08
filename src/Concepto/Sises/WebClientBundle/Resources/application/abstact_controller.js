/**
 * Created by julian on 8/09/14.
 */
;
(function () {
    "use strict";

    function ListController (scope, Factory) {
        scope.elements = Factory.query();

        scope.details = function () {
            throw "details() Not implemented!";
        };

        scope.add = function() {
            throw "add() Not implemented!";
        }
    }

    function CoreController(scope) {
        scope.errors = {};
        scope.canSave = true;

        scope.list = function() {
            throw " list() Not implemented!";
        };

        scope.testSave = function() {
            return !scope.canSave;
        };

        scope.save = function() {
            scope.canSave = false;
            scope.element.$save(saveSuccess, saveFail);
        };

        var saveSuccess = function() {
            scope.list();
        };

        var setErrors = function(errors) {
            scope.errors = errors;
        };

        var saveFail = function(response) {
            switch (response.data.code) {
                case 400:
                    setErrors(response.data.errors.children);
                    break;
                default:
                    console.error(response);
                    break;
            }
            scope.canSave = true;
        };
    }

    function UpdateController (scope, Factory) {
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
            scope.element.$update(saveSuccess, saveFail);
        };
    }

    UpdateController.prototype = Object.create(CoreController.prototype);


    function NewController (scope, Factory) {
        CoreController.call(this, scope);
        scope.element = new Factory();

    }

    G.Base = {
        CoreController: CoreController,
        NewController: NewController,
        ListController: ListController,
        UpdateController: UpdateController
    };
})();
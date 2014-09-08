/**
 * Created by julian on 8/09/14.
 */
;
(function () {
    "use strict";

    function ListController (Factory) {
        this.elements = Factory.query();

        this.details = function () {
            throw "details() Not implemented!";
        };
    }

    function NewController (scope, Factory) {
        scope.element = new Factory();
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
            scope.canSave = true;
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

    G.Base = {
        NewController: NewController,
        ListController: ListController
    };
})();
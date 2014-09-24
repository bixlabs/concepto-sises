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

    G.modules.PROFILE = 'PROFILE';

    angular.module(G.modules.PROFILE, ['ngResource'])
        .factory('sisesHttpInterceptor', ['$q', '$injector', '$rootScope', function($q, $injector, $rootScope) {
            var $state;
            return {
                request: function(config) {
                    config.headers = angular.extend(config.headers, {
                        'X-Api-Token': $rootScope.authToken
                    });
                    return config || $q.when(config);
                },
                response: function(response) {

                    return response || $q.when(response);
                },
                responseError: function(response) {
                    if (response.status == 401) {
                        // delayed inject avoid circular dependency
                        $state = $state || $injector.get('$state');
                        $rootScope.authState = false;
                        $rootScope.authToken = '';
                        $state.go('login');
                    }

                    return $q.reject(response);
                }
            };
        }])
        .config(['$stateProvider', '$httpProvider', function($stateProvider, $httpProvider) {
            $stateProvider
                .state('login', {
                    url: '/login',
                    controller: 'ProfileLoginController',
                    templateUrl: G.template('profile/login')
                });

            $httpProvider.interceptors.push('sisesHttpInterceptor');
        }])

        .run(['$rootScope', '$state', function($rootScope, $state) {
            $rootScope.$on('$stateChangeStart',
                function(event, toState, toParams, fromState, fromParams) {
                    if (!$rootScope.authToken && toState.name !== 'login') {
                        event.preventDefault();
                        $rootScope.lastState = toState;
                        $state.go('login');
                    }
                });
        }])
        .controller('ProfileLoginController', ['$scope', '$resource','$rootScope', function($scope, $resource, $r) {
            var url = G.json_route('/login_check'),
                Login = $resource(url, {}, {}, {stripTrailingSlashes: false});

            $scope.element = new Login();

            $scope.login = function() {
                $scope.element.$save(function(data, headers) {
                    var name;

                    $r.authToken = headers('X-Api-Token');
                    $r.authState = true;
                    if ($r.lastState) {
                        name = $r.lastState.name;
                        $r.lastState = null;
                    } else {
                        name = 'dashboard';
                    }
                    $scope.go(name);
                }, function() {
                    $r.authToken = '';
                });
            };
        }])
    ;
})();
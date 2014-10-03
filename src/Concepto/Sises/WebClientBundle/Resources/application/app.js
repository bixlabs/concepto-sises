/**
 * Created by julian on 4/09/14.
 */
(function() {
    "use strict";

    var modules = [
        'EMPRESA',
        'DASHBOARD',
        'CONTRATO',
        'BENEFICIARIO',
        'RRHH',
        'COORDINADOR',
        'DIRECTOR',
        'PROFILE',
        'ui.router',
        'localytics.directives',
        'plupload.directive',
        'ui.bootstrap'
    ];

    if (G.json_route('') !== '') {
        modules.push('PROFILE');
    }

    angular.module(G.APP, modules)
        .config(['plUploadServiceProvider', '$provide', function (plUploadServiceProvider, $provide) {
            //plUploadServiceProvider.setConfig('flashPath', 'bower_components/plupload-angular-directive/plupload.flash.swf');
            //plUploadServiceProvider.setConfig('silverLightPath', 'bower_components/plupload-angular-directive/plupload.silverlight.xap');
            plUploadServiceProvider.setConfig('uploadPath', G.route('_uploader_upload_documentable'));

            // Format monkeypatch angular ui bootstrap datepicker
            $provide.decorator('dateParser', function($delegate){

                var oldParse = $delegate.parse;
                $delegate.parse = function(input, format) {
                    if ( !angular.isString(input) || !format ) {
                        return input;
                    }
                    return oldParse.apply(this, arguments);
                };

                return $delegate;
            });
        }])
        .run(['$rootScope', '$state', '$stateParams', 'modalService', function ($r, $state, $sP, mS) {
            $r.authState = false;
            $r.go = $state.go;
            G.stateGo = $state.go;
            $r.refresh = function(state, params) {
                $state.go(state, params, {
                    reload: true,
                    inherit: false,
                    notify: true
                })
            };
            $r.template = G.template;
            $r.routeParams = $sP;
            $r.modal = mS;
        }])
    ;
})();
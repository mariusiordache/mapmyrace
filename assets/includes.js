require.config({
    waitSeconds: 200,
    config: {
        moment: {
            noGlobal: false
        },
        bootstro: {
            noGlobal: false
        },
        typeaheadjs: {
            deps: [
                'jquery',
                'bootstrap'
            ],
            exports: '$.fn.typeahead'
        }
    },
    shim: {
        jquery: {
            exports: '$'
        },
        backbone: {
            deps: [
                'underscore',
                'jquery'
            ],
            exports: 'Backbone'
        },
        underscore: {
            exports: '_'
        },
        bootstrap: {
            deps: [
                'jquery'
            ]
        },
        typeaheadjs: {
            deps: [
                'jquery',
                'bootstrap'
            ],
            exports: '$.fn.typeahead'
        },
        'bootstrap-select': {
            deps: [
                'bootstrap',
                'jquery'
            ]
        },
        'jquery.fileupload': {
            deps: [
                'canvas-to-blob',
                'tmpl'
            ]
        },
        jqueryslimscroll: {
            deps: [
                'jquery'
            ]
        },
        highcharts: {
            deps: [
                'jquery'
            ]
        },
        'jquery.countdown': {
            deps: [
                'jquery'
            ]
        },
        'bootstrap-daterangepicker': {
            deps: [
                'bootstrap',
                'jquery'
            ]
        },
        'jquery-colorbox': {
            deps: [
                'bootstrap',
                'jquery'
            ]
        },
        'bower-jquery-easing': {
            deps: [
                'jquery'
            ]
        },
        parallax: {
            deps: [
                'jquery'
            ]
        },
        bootstro: {
            deps: [
                'bootstrap'
            ],
            exports: 'bootstro'
        }
    },
    baseUrl: '/',
    paths: {
        backbone: 'bower_components/backbone/backbone',
        bootstrap: 'bower_components/bootstrap/dist/js/bootstrap',
        jquery: 'bower_components/jquery/dist/jquery',
        'jquery.postmessage-transport': 'bower_components/jqueryfileupload/js/cors/jquery.postmessage-transport',
        'jquery.xdr-transport': 'bower_components/jqueryfileupload/js/cors/jquery.xdr-transport',
        'jquery.ui.widget': 'bower_components/jqueryfileupload/js/vendor/jquery.ui.widget',
        'jquery.fileupload': 'bower_components/jqueryfileupload/js/jquery.fileupload',
        'jquery.fileupload-process': 'bower_components/jqueryfileupload/js/jquery.fileupload-process',
        'jquery.fileupload-validate': 'bower_components/jqueryfileupload/js/jquery.fileupload-validate',
        'jquery.fileupload-image': 'bower_components/jqueryfileupload/js/jquery.fileupload-image',
        'jquery.fileupload-audio': 'bower_components/jqueryfileupload/js/jquery.fileupload-audio',
        'jquery.fileupload-video': 'bower_components/jqueryfileupload/js/jquery.fileupload-video',
        'jquery.fileupload-ui': 'bower_components/jqueryfileupload/js/jquery.fileupload-ui',
        'jquery.fileupload-jquery-ui': 'bower_components/jqueryfileupload/js/jquery.fileupload-jquery-ui',
        'jquery.fileupload-angular': 'bower_components/jqueryfileupload/js/jquery.fileupload-angular',
        'jquery.iframe-transport': 'bower_components/jqueryfileupload/js/jquery.iframe-transport',
        jqueryui: 'bower_components/jqueryui/jquery-ui',
        marionette: 'bower_components/marionette/lib/core/backbone.marionette',
        moment: 'bower_components/moment/moment',
        requirejs: 'bower_components/requirejs/require',
        underscore: 'bower_components/underscore/underscore',
        jqueryslimscroll: 'bower_components/jqueryslimscroll/jquery.slimscroll.min',
        'bootstrap-hover-dropdown': 'bower_components/bootstrap-hover-dropdown/bootstrap-hover-dropdown',
        'jquery-validation': 'bower_components/jquery-validation/dist/jquery.validate',
        socketio: 'bower_components/socket.io-client/socket.io',
        'canvas-to-blob': 'bower_components/blueimp-canvas-to-blob/js/canvas-to-blob',
        'load-image': 'bower_components/blueimp-load-image/js/load-image',
        'load-image-ios': 'bower_components/blueimp-load-image/js/load-image-ios',
        'load-image-orientation': 'bower_components/blueimp-load-image/js/load-image-orientation',
        'load-image-meta': 'bower_components/blueimp-load-image/js/load-image-meta',
        'load-image-exif': 'bower_components/blueimp-load-image/js/load-image-exif',
        'load-image-exif-map': 'bower_components/blueimp-load-image/js/load-image-exif-map',
        tmpl: 'bower_components/blueimp-tmpl/js/tmpl',
        'backbone.babysitter': 'bower_components/backbone.babysitter/lib/backbone.babysitter',
        'backbone.wreqr': 'bower_components/backbone.wreqr/lib/backbone.wreqr',
        'bootstrap-select': 'bower_components/bootstrap-select/dist/js/bootstrap-select',
        typeaheadjs: 'bower_components/typeahead.js/dist/typeahead.jquery',
        bloodhound: 'bower_components/typeahead.js/dist/bloodhound',
        'feather-aviary': 'bower_components/feather-aviary/index',
        'bootstrap-colorpicker': 'bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min',
        simpleStorage: 'bower_components/simpleStorage/simpleStorage',
        'jquery.event.swipe': 'bower_components/jquery.event.swipe/js/jquery.event.swipe',
        'jquery.event.move': 'bower_components/jquery.event.move/js/jquery.event.move',
        'blueimp-tmpl': 'bower_components/blueimp-tmpl/js/tmpl',
        'bootstrap-daterangepicker': 'bower_components/bootstrap-daterangepicker/daterangepicker',
        'blueimp-canvas-to-blob': 'bower_components/blueimp-canvas-to-blob/js/canvas-to-blob',
        'jquery-colorbox': 'bower_components/jquery-colorbox/jquery.colorbox',
        highcharts: 'bower_components/highcharts/highcharts',
        'highcharts-more': 'bower_components/highcharts/highcharts-more',
        exporting: 'bower_components/highcharts/modules/exporting',
        raphael: 'assets/raphael/raphael-min',
        'raphael-sketchpad': 'assets/raphael/raphael/raphael.sketchpad.js',
        'jquery.countdown': 'bower_components/jquery.countdown/dist/jquery.countdown',
        'bower-jquery-easing': 'bower_components/bower-jquery-easing/js/jquery.easing',
        parallax: 'bower_components/parallax/deploy/parallax.min',
        'jquery.parallax': 'bower_components/parallax/deploy/jquery.parallax.min'
    },
    packages: [

    ]
});

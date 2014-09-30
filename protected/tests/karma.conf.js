// Karma configuration
// Generated on Sun Sep 28 2014 13:29:02 GMT-0700 (PDT)

module.exports = function(config) {
  config.set({

    // base path that will be used to resolve all patterns (eg. files, exclude)
    basePath: '../../',


    // frameworks to use
    // available frameworks: https://npmjs.org/browse/keyword/karma-adapter
    frameworks: ['jasmine'],


    // list of files / patterns to load in the browser
    files: [
        //JASMINE,
        //JASMINE_ADAPTER,
        'ng-search/app/lib/angular/angular.js',
        'ng-search/app/lib/angular/angular-*.js',
        'ng-search/app/lib/ui-bootstrap/ui-bootstrap.min.js',
        'ng-search/app/lib/ya-map/ya-map.min.js',
        'ng-search/app/lib/angular-file-upload.min.js',
        'ng-search/app/partials/directives/*.html',
        'ng-search/app/js/**/*.js',
        'protected/tests/lib/angular-mocks.js',
        'protected/tests/lib/stateMock.js',
        'protected/tests/unit/js/*Spec.js'
    ],


    // list of files to exclude
    exclude: [
    ],


    // preprocess matching files before serving them to the browser
    // available preprocessors: https://npmjs.org/browse/keyword/karma-preprocessor
    preprocessors: {
    },


    // test results reporter to use
    // possible values: 'dots', 'progress'
    // available reporters: https://npmjs.org/browse/keyword/karma-reporter
    reporters: ['progress'],


    // web server port
    port: 9876,


    // enable / disable colors in the output (reporters and logs)
    colors: true,


    // level of logging
    // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
    logLevel: config.LOG_INFO,


    // enable / disable watching file and executing tests whenever any file changes
    autoWatch: false,


    // start these browsers
    // available browser launchers: https://npmjs.org/browse/keyword/karma-launcher
    browsers: ['Chrome'],

    plugins: [
        'karma-chrome-launcher',
        'karma-jasmine'
    ],
    // Continuous Integration mode
    // if true, Karma captures browsers, runs the tests and exits
    singleRun: false
  });
};

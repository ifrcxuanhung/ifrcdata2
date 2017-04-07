// Author: LongNguyen
// Filename: main.js

// Require.js allows us to configure shortcut alias
require.config({
    paths: {
        jquery: '../../bundles/jquery-1.7.2.min',
        underscore: 'libs/underscore/underscore-min',
        backbone: 'libs/backbone/backbone-optamd3-min',
        text: 'libs/require/text',
        browserPlus: '../../bundles/browserplus-min',
        plupload: '../../bundles/plupload/js/plupload.full',
        pluploadQueue: '../../bundles/plupload/js/jquery.plupload.queue/jquery.plupload.queue',
        templates: 'templates'
    }
});

require([
    'general'
    ,'app'
    // Some plugins have to be loaded in order due to their non AMD compliance
    // Because these scripts are not "modules" they do not pass any values to the definition function below
    ], function(general,App){
        // The "app" dependency is passed in as "App"
        // Again, the other dependencies passed in are not "AMD" therefore don't pass a parameter to this function
        App.initialize();
    });

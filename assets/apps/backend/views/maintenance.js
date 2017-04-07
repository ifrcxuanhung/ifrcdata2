// Filename: views/cpaction
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var maintenanceListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {

            },

            index: function(){
                $(document).on("click","#submit",function(){
                    var date = $("#date").val();
                    var type = $("#select").val();
                    $(location).attr('href', $admin_url + "maintenance/index?date="+date+"&type="+type);
                });
            },

            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return maintenanceListView = new maintenanceListView;
    });

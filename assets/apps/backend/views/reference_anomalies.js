define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var $html='<img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/>';
        var reference_anomaliesView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
            },
            index: function(){
            },
            check:function(){
                $('#lean_overlay').show(100,function(){
                    $.ajax({
                        type: "POST",
                        url: $admin_url+"reference/reference_anomalies",
                        data:'',
                        success: function(data){
                            var $data=JSON.parse(data);
                            $('#lean_overlay').hide();
                            openModal('Anomalies',$data,400);
                        }
                    });
                });
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return reference_anomaliesView = new reference_anomaliesView;
    });

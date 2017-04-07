define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/anomalies/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/>';
        var anomaliesView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
            },
            index: function(){
                $(document).ready(function()
                {
                    alert('vo');
                });
            },
            calculationHistoryAll:function(){
                $.ajax({
                    type: "POST",
                    url: $admin_url+"backhistory_calculation/all",
                    data:'action=all',
                    success: function(data){
                        var datatemplate={};
                        datatemplate.report=JSON.parse(data);
                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                        $('.backhistory-calculation-report').html(compiledTemplate).fadeIn();
                    }
                });
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return anomaliesView = new anomaliesView;
    });

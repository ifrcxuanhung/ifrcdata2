define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/backhistory_calculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="backhistory-calculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var calculationHitoryView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
            },
            index: function(){
                $(document).ready(function()
                {
                    console.log('index');
                });
            },
            calculationHistoryAll:function(){
                // xy ly cho action all
                openModal('Backhistory_calculation',$html,400);
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
        return calculationHitoryView = new calculationHitoryView;
    });

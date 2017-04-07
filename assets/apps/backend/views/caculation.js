define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var caculationView = Backbone.View.extend({
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
            caculationAll:function(){
                // xy ly cho action all
                openModal('Calculation',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"caculation/all",
                    data:'action=all',
                    success: function(data){
                        var datatemplate={};
                        datatemplate.report=JSON.parse(data);
                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                        $('.caculation-report').html(compiledTemplate).fadeIn();
                    }
                });
            },
            adjustment:function(){
                openModal('Adjustment',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"caculation/adjustment",
                    data:'action=all',
                    success: function(data){
                      //  var datatemplate={};
                       // datatemplate.report=JSON.parse(data);
                        console.log(data);

                    //var compiledTemplate = _.template( reportTemplate, datatemplate );
                    $('.caculation-report').html('Done').fadeIn();
                    }
                });
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return caculationView = new caculationView;
    });

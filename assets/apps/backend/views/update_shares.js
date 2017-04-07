define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone, reportTemplate){
        var $html='<div class="action-update-shares-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var updatesharesView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                'click .update-shares' : 'doUpdateShares'
            },
            doUpdateShares:function(event){
                openModal('Update shares',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"update_shares",
                    data:'',
                    success: function(data){
                        var datatemplate={};
                        datatemplate.report=JSON.parse(data);
                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                        $('.action-update-shares-report').html(compiledTemplate).fadeIn();
                    }
                });
            },
            index: function(){
                $(document).ready(function()
                {
                    console.log('index');
                });
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return new updatesharesView;
    });

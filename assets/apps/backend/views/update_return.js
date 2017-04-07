define([
    'jquery',
    'underscore',
    'backbone',
    ], function($, _, Backbone){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var updateReturnView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
            },
            insert_data:function(){
                openModal('Insert data',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"update_return/insert_data",
                    dataType: "json",
                    data:'',
                    success: function(data){
                        $('.caculation-report').html(data);
                    }
                });
            },
            clear_data:function(){
                openModal('Clear data',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"update_return/clear_data",
                    dataType: "json",
                    data:'',
                    success: function(data){
                        $('.caculation-report').html(data);
                    }
                });
            },
            calculate_return:function(){
                openModal('Calculate return',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"update_return/calculate_return",
                    dataType: "json",
                    data:'',
                    success: function(data){
                        $('.caculation-report').html(data);
                    }
                });
            },
            adjusted_price:function(){
                openModal('Adjusted price',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"update_return/adjusted_price",
                    dataType: "json",
                    data:'',
                    success: function(data){
                        $('.caculation-report').html(data);
                    }
                });
            },
            index: function(){
                openModal('Update Return',$html,400);
                $.ajax({
                    type: "POST",
                    url: $admin_url+"update_return",
                    dataType: "json",
                    data:'',
                    success: function(data){
                        $('.caculation-report').html(data);
                    }
                });
            },
            caculationAll:function(){
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return updateReturnView = new updateReturnView;
    });

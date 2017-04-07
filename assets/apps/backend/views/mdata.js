define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var mdataView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click #save": "excute",

            },

            excute: function(e){
                
            },
            index: function(){
                $(document).ready(function()
                {
                    console.log('index');
                });
            },
            openModal: function openModal($title,$content,$width)
            {
                $.modal({
                    content: $content,
                    title: $title,
                    maxWidth: 2500,
                    width: $width,
                    buttons: {
                        'Close': function(win) {
                            window.location.href = $admin_url;
                        }
                    }
                });
            },
            all: function(){
                $(document).ready(function(){
                    mdataView.openModal('Mdata All', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'mdata/process_all',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });
                });
            },
            price_history: function(){
                var vndbPricesHistoryView;
                require(['views/vndb_prices_history'], function(obj){
                    vndbPricesHistoryView = obj;
                });
                $(document).ready(function(){
                    mdataView.openModal('Setting', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'mdata/process_get_setting',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                var value = datatemplate.report.value;
                                if(value == 1){
                                    $('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>Setting is 1, Do you want continue?</div>"
                                        +"<div class='button' style='width:37%'><button id='accept' style='float:left;margin-right:20%;'>Yes</button><button id='de-accept'>No</button></div>").fadeIn();
                                    $('#accept').click(function(){
                                        $("#modal").remove();
                                        vndbPricesHistoryView.doPricesHistory();
                                    });
                                }else{
                                    $("#modal").remove();
                                    mdataView.openModal('Price History', $html, 400);
                                    $("#modal").show(0, function(){
                                        $.ajax({
                                            url: $admin_url + 'mdata/process_price_history',
                                            type: 'post',
                                            async: false,
                                            success: function(data){
                                                var datatemplate={};
                                                datatemplate.report=JSON.parse(data);
                                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                                $('.caculation-report').html(compiledTemplate).fadeIn();
                                            }
                                        });
                                    });
                                }
                            }
                        });
                    });
                }); 
            },
            create: function(){
                $(document).ready(function(){
                    mdataView.openModal('Create Mdata', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'mdata/process_create',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });
                });
            },
            create_qidx: function(){
                $(document).ready(function(){
                    mdataView.openModal('Create Qidx', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'mdata/process_create_qidx',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });
                });
            },
           calculation: function(){
                $(document).ready(function(){
                    mdataView.openModal('Calculation Mdata', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'mdata/process_calculation',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });
                });
            },
            update: function(){
                $(document).ready(function(){
                    mdataView.openModal('Update Mdata', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'mdata/process_update',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });
                });
            },
            update_dividend: function(){
                $(document).ready(function(){
                    mdataView.openModal('Update Dividend', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'mdata/process_update_dividend',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });
                });
            },
            update_event: function(){
                $(document).ready(function(){
                    mdataView.openModal('Update Event', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'mdata/process_update_event',
                            type: 'post',
                            async: false,
                            success: function(data){
                                var datatemplate={};
                                datatemplate.report=JSON.parse(data);
                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });
                });
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return mdataView = new mdataView;
    });

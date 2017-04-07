define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var etfView = Backbone.View.extend({
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
            download_etf_country: function(){
                $(document).ready(function(){
                    etfView.openModal('Confirm Download ETF Country', $html, 400);
                    $("#modal").show(0, function(){
                        $('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>Do you want run this process?</div>"
                            +"<div class='button' style='width:37%'><button id='accept' style='float:left;margin-right:20%;'>Yes</button><button id='de-accept'>No</button></div>").fadeIn();
                        $('#accept').click(function(){
                            $("#modal").remove();
                            etfView.openModal('Download ETF Country', $html, 400);
                            $("#modal").show(0, function(){
                                $.ajax({
                                     url: $admin_url + 'etf/process_download_etf_country',
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
                        $('#de-accept').click(function(){
                            window.location.href = $admin_url;
                        });
                    });
                });
            },
            download_etf_screener: function(){
                $(document).ready(function(){
                    etfView.openModal('Confirm Download ETF Screener', $html, 400);
                    $("#modal").show(0, function(){
                        $('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>Do you want run this process?</div>"
                            +"<div class='button' style='width:37%'><button id='accept' style='float:left;margin-right:20%;'>Yes</button><button id='de-accept'>No</button></div>").fadeIn();
                        $('#accept').click(function(){
                            $("#modal").remove();
                            etfView.openModal('Download ETF Screener', $html, 400);
                            $("#modal").show(0, function(){
                                $.ajax({
                                    url: $admin_url + 'etf/process_download_etf_screener',
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
                        $('#de-accept').click(function(){
                            window.location.href = $admin_url;
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
        return etfView = new etfView;
    });

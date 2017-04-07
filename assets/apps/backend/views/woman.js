define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var womanView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){

            },
            events: {

            },

            excute: function(e){

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

            get_ceo_all: function(){
                $.modal({
                    content: 'Are you sure?',
                    title: 'Confirm',
                    maxWidth: 2500,
                    width: 400,
                    buttons: {
                        'Ok': function(win) {
                            $("#modal").remove();
                            womanView.openModal('Download CEO', $html, 400);
                            $("#modal").show(0, function(){
                                $.ajax({
                                    url: $admin_url + 'woman/process_get_ceo_all',
                                    async: false,
                                    success: function(data){
                                        var datatemplate={};
                                        datatemplate.report=JSON.parse(data);
                                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                                        $('.caculation-report').html(compiledTemplate).fadeIn();
                                        if(datatemplate.report[0].report != ''){
                                            $.each(datatemplate.report[0].report, function(k, item){
                                                $('.blocks-list').append("<li><span style='color:green'>Report</span>: "+item+"</li>");
                                            });
                                            $(".block-footer").append("<button type='button' style='margin-left:10px'>Import</button>");
                                            $('.modal-window .block-content .block-footer').find('button:eq(1)').attr('class', 'red');
                                            $(".block-footer button").last().click(function(){
                                                $("#modal").remove();
                                                womanView.openModal('Download CEO', $html, 400);
                                                $("#modal").show(0, function(){
                                                    $.ajax({
                                                        url: $admin_url + 'woman/process_import_ceo',
                                                        async: false,
                                                        success: function(rs){
                                                            var datatemplate={};
                                                            datatemplate.report=JSON.parse(rs);
                                                            var compiledTemplate = _.template( reportTemplate, datatemplate );
                                                            $('.caculation-report').html(compiledTemplate).fadeIn();
                                                        }
                                                    })
                                                });
                                            });
                                        }
                                    }
                                });
                            });
                        },
                        'Cancel': function(win) {
                            //win.closeModal();
                            window.location.href = $admin_url;
                        }
                    }
                });
                $('.modal-window .block-content .block-footer').find('button:eq(1)').attr('class', 'red');
            },

            download_ceo: function(){
                $.modal({
                    content: 'Are you sure?',
                    title: 'Confirm',
                    maxWidth: 2500,
                    width: 400,
                    buttons: {
                        'Ok': function(win) {
                            $("#modal").remove();
                            womanView.openModal('Download CEO', $html, 400);
                            $("#modal").show(0, function(){
                                $.ajax({
                                    url: $admin_url + 'woman/process_download_ceo',
                                    async: false,
                                    success: function(data){
                                        var datatemplate={};
                                        datatemplate.report=JSON.parse(data);
                                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                                        $('.caculation-report').html(compiledTemplate).fadeIn();
                                    }
                                });
                            });
                        },
                        'Cancel': function(win) {
                            //win.closeModal();
                            window.location.href = $admin_url;
                        }
                    }
                });
                $('.modal-window .block-content .block-footer').find('button:eq(1)').attr('class', 'red');
            },

            compare_ceo: function(){
                $.modal({
                    content: 'Are you sure?',
                    title: 'Confirm',
                    maxWidth: 2500,
                    width: 400,
                    buttons: {
                        'Ok': function(win) {
                            $("#modal").remove();
                            womanView.openModal('Compare CEO', $html, 400);
                            $("#modal").show(0, function(){
                                $.ajax({
                                    url: $admin_url + 'woman/process_compare_ceo',
                                    async: false,
                                    success: function(data){
                                        var datatemplate={};
                                        datatemplate.report=JSON.parse(data);
                                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                                        $('.caculation-report').html(compiledTemplate).fadeIn();
                                        if(datatemplate.report[0].report != ''){
                                            $.each(datatemplate.report[0].report, function(k, item){
                                                $('.blocks-list').append("<li><span style='color:green'>Report</span>: "+item+"</li>");
                                            });
                                        }
                                    }
                                });
                            });
                        },
                        'Cancel': function(win) {
                            //win.closeModal();
                            window.location.href = $admin_url;
                        }
                    }
                });
                $('.modal-window .block-content .block-footer').find('button:eq(1)').attr('class', 'red');
            },

            import_ceo: function(){
                $.modal({
                    content: 'Are you sure?',
                    title: 'Confirm',
                    maxWidth: 2500,
                    width: 400,
                    buttons: {
                        'Ok': function(win) {
                            $("#modal").remove();
                            womanView.openModal('Import CEO', $html, 400);
                            $("#modal").show(0, function(){
                                $.ajax({
                                    url: $admin_url + 'woman/process_import_ceo',
                                    async: false,
                                    success: function(data){
                                        var datatemplate={};
                                        datatemplate.report=JSON.parse(data);
                                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                                        $('.caculation-report').html(compiledTemplate).fadeIn();
                                    }
                                });
                            });
                        },
                        'Cancel': function(win) {
                            //win.closeModal();
                            window.location.href = $admin_url;
                        }
                    }
                });
                $('.modal-window .block-content .block-footer').find('button:eq(1)').attr('class', 'red');
            },

            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return womanView = new womanView;
    });

define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var databaseView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){

            },
            events: {
                "click .action-clean": "doClean",
            },

            excute: function(e){

            },

            doClean: function(event){
                var $this=$(event.currentTarget);
                var action = $($this).attr('action');
                switch(action){
                    case 'structure':
                        $.modal({
                            content: 'Are you sure?',
                            title: 'Confirm',
                            maxWidth: 2500,
                            width: 400,
                            buttons: {
                                'Ok': function(win) {
                                    $("#modal").remove();
                                    databaseView.openModal('Clean Struture', $html, 400);
                                    $("#modal").show(0, function(){
                                        $.ajax({
                                            url: $admin_url + 'database/cleanStruture',
                                            async: false,
                                            success: function(data){
                                                var datatemplate={};
                                                datatemplate.report=JSON.parse(data);
                                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                                $('.caculation-report').html(compiledTemplate).fadeIn();
                                                if(datatemplate.report[0].success != ''){
                                                    $.each(datatemplate.report[0].success, function(k, item){
                                                        $('.blocks-list').append("<li><span style='color:green'>Notificate</span>: "+item+"</li>");
                                                    });
                                                }
                                                if(datatemplate.report[0].error != 0){
                                                    $.each(datatemplate.report[0].error, function(k, item){
                                                        $('.blocks-list').append("<li><span style='color:red'>Warning</span>: "+item+"</li>");
                                                    });
                                                }
                                            }
                                        });
                                    });
                                },
                                'Cancel': function(win) {
                                    win.closeModal();
                                }
                            }
                        });
                        $('.modal-window .block-content .block-footer').find('button:eq(1)').attr('class', 'red');
                    break;
                    case 'index':
                        $.modal({
                            content: 'Are you sure?',
                            title: 'Confirm',
                            maxWidth: 2500,
                            width: 400,
                            buttons: {
                                'Ok': function(win) {
                                    $("#modal").remove();
                                    databaseView.openModal('Clean Index', $html, 400);
                                    $("#modal").show(0, function(){
                                        $.ajax({
                                            url: $admin_url + 'database/cleanIndex',
                                            async: false,
                                            success: function(data){
                                                var datatemplate={};
                                                datatemplate.report=JSON.parse(data);
                                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                                $('.caculation-report').html(compiledTemplate).fadeIn();
                                                if(datatemplate.report[0].success != ''){
                                                    $.each(datatemplate.report[0].success, function(k, item){
                                                        $('.blocks-list').append("<li><span style='color:green'>Notificate</span>: "+item+"</li>");
                                                    });
                                                }
                                            }
                                        });
                                    });
                                },
                                'Cancel': function(win) {
                                    win.closeModal();
                                }
                            }
                        });
                        $('.modal-window .block-content .block-footer').find('button:eq(1)').attr('class', 'red');
                    break;
                    default:
                    break;
                }
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

            index: function(){
                var $content = '<ul>'+
                '<li style="margin-bottom:10px">View: Show Stucture and Index</li>'+
                '<li>Download: Get Stucture and Index Of Database and Table</li>'+
                '</ul>';
                $.modal({
                    content: $content,
                    title: 'Database',
                    maxWidth: 2500,
                    width: 400,
                    buttons: {
                        'View': function(win) {
                            window.location.href = $admin_url+'database/view';
                        },
                        'Download': function(win) {
                            $("#modal").remove();
                            databaseView.openModal('Step 1: Download', $html, 400);
                            $("#modal").show(0, function(){
                                $.ajax({
                                    url: $admin_url + 'database/download',
                                    async: false,
                                    success: function(data){
                                        var datatemplate={};
                                        datatemplate.report=JSON.parse(data);
                                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                                        $('.caculation-report').html(compiledTemplate).fadeIn();
                                        if(datatemplate.report[0].success != ""){
                                            $('.blocks-list').append("<li><span style='color:green'>Notificate:</span> "+datatemplate.report[0].success+"</li>"); 
                                        }
                                        if(datatemplate.report[0].report != 0){
                                            $.each(datatemplate.report[0].report, function(k, item){
                                                $('.blocks-list').append("<li><span style='color:blue'>Report</span>: "+item+"</li>");
                                            });
                                        }
                                        if(datatemplate.report[0].error != 0){
                                            $.each(datatemplate.report[0].error, function(k, item){
                                                $('.blocks-list').append("<li><span style='color:red'>Error</span>: "+item+"</li>");
                                            });
                                        }
                                        $(".block-footer").append("<button type='button' style='margin-left:10px'>Correct</button>");
                                        $('.modal-window .block-content .block-footer').find('button:eq(1)').attr('class', 'red');

                                        $(".block-footer button").first().click(function(){
                                            window.location.href = $admin_url+'database/view';
                                        });

                                        $(".block-footer button").last().click(function(){
                                            databaseView.correct();
                                        });
                                    }
                                });
                            });
                        },

                        'Correct': function(){
                            databaseView.correct();
                        },

                        'Cancel': function(win) {
                            window.location.href = $admin_url;
                        }
                        
                    }
                });
                $('.modal-window .block-content .block-footer').find('button:eq(1),button:eq(2)').attr('class', 'red');
            },
            correct: function(){
                $("#modal").remove();
                databaseView.openModal('Step 2: Correct', $html, 400);
                $("#modal").show(0, function(){
                    $.ajax({
                        url: $admin_url + 'database/correct',
                        async: true,
                        success: function(data){
                            var datatemplate={};
                            datatemplate.report=JSON.parse(data);
                            var compiledTemplate = _.template( reportTemplate, datatemplate );
                            $('.caculation-report').html(compiledTemplate).fadeIn();
                            if(datatemplate.report[0].success != ""){
                                $('.blocks-list').append("<li><span style='color:green'>Notificate:</span> "+datatemplate.report[0].success+"</li>"); 
                            }
                            if(datatemplate.report[0].report != 0){
                                $.each(datatemplate.report[0].report, function(k, item){
                                    $('.blocks-list').append("<li><span style='color:blue'>Report</span>: "+item+"</li>");
                                });
                            }
                            $(".block-footer button").first().click(function(){
                                window.location.href = $admin_url+'database/view';
                            });
                        },
                        error: function(jqXHR, exception) {
                            var $message = "";
                            if (jqXHR.status === 0) {
                                $message = 'Not connect.\n Verify Network.';
                            } else if (jqXHR.status == 404) {
                                $message = 'Requested page not found. [404]';
                            } else if (jqXHR.status == 500) {
                                $message = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                $message = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                $message = 'Time out error.';
                            } else if (exception === 'abort') {
                                $message = 'Ajax request aborted.';
                            } else {
                                $message = 'Uncaught Error.\n' + jqXHR.responseText;
                            }
                            $message += '\n'+jqXHR.responseText+'\nPlease contact for administartor, thanks';
                            $("#modal").remove();
                            databaseView.openModal('Step 2: Error', $message, 400);
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
        return databaseView = new databaseView;
    });

define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var vnxView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            // openModal('Update Indexes', $html, 400);
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
            hight_dividend: function(){
                $(document).ready(function(){
                    $.modal({
                        content: 'Are you sure?',
                        title: 'Confirm',
                        maxWidth: 2500,
                        width: 400,
                        buttons: {
                            'Ok': function(win) {
                                $("#modal").remove();
                                vnxView.openModal('Hight Dividend', $html, 400);
                                $("#modal").show(0, function(){
                                    $.ajax({
                                        url: $admin_url + 'vnx/process_hight_dividend',
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
                            },
                            'Cancel': function(win) {
                                //win.closeModal();
                                window.location.href = $admin_url;
                            }
                        }
                    })
                    $('.modal-window .block-content .block-footer').find('button:eq(1)').attr('class', 'red');
                });
            },

			low_volalitily: function(){
                $(document).ready(function(){
                    $.modal({
                        content: 'Are you sure?',
                        title: 'Confirm',
                        maxWidth: 2500,
                        width: 400,
                        buttons: {
                            'Ok': function(win) {
                                $("#modal").remove();
                                vnxView.openModal('Low Volalitily', $html, 400);
                                $("#modal").show(0, function(){
                                    $.ajax({
                                        url: $admin_url + 'vnx/process_low_volalitily',
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
                            },
                            'Cancel': function(win) {
                                //win.closeModal();
                                window.location.href = $admin_url;
                            }
                        }
                    })
                    $('.modal-window .block-content .block-footer').find('button:eq(1)').attr('class', 'red');
                });
            },
			hight_volalitily: function(){
                $(document).ready(function(){
                    $.modal({
                        content: 'Are you sure?',
                        title: 'Confirm',
                        maxWidth: 2500,
                        width: 400,
                        buttons: {
                            'Ok': function(win) {
                                $("#modal").remove();
                                vnxView.openModal('Hight Volalitily', $html, 400);
                                $("#modal").show(0, function(){
                                    $.ajax({
                                        url: $admin_url + 'vnx/process_hight_volalitily',
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
                            },
                            'Cancel': function(win) {
                                //win.closeModal();
                                window.location.href = $admin_url;
                            }
                        }
                    })
                    $('.modal-window .block-content .block-footer').find('button:eq(1)').attr('class', 'red');
                });
            },
			equal_weighted_50: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    vnxView.openModal('Equal Weighted 50', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'vnx/process_equal_weighted_50',
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
			equal_weighted_25: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    vnxView.openModal('Equal Weighted 25', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'vnx/process_equal_weighted_25',
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
        return vnxView = new vnxView;
    });

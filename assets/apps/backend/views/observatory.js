define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var observatoryView = Backbone.View.extend({
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
            import_currency: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    observatoryView.openModal(trans('mn_import_currency', 1), $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'observatory/process_import_currency',
                            type: 'POST',
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
            import_economics: function(){
                $(document).ready(function(){
                    //$('#lean_overlay').show('0',function(){
                    observatoryView.openModal('Import Economics', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'observatory/process_import_economics',
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
        return observatoryView = new observatoryView;
    });

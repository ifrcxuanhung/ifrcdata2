define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html_loading='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var updateView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){

            },
            events: {
                "click .run": "Run",
                
            },

            excute: function(e){
                
            },
            index: function(){

            },
            Run: function(event){
                
                var $this = $(event.currentTarget);
                var action = $this.attr('action');
                var name;
                switch(action){
                    case 'compo':
                        name = 'update_idx_compo';
                    break;
                    case 'specs':
                        name = 'update_idx_specs';
                    break;
                    case 'vnx':
                        name = 'update_idx_vnx';
                    break;
                    case 'upload_data':
                        name = 'upload_data_forday';
                    break;                    
                    case 'back_data':
                        name = 'update_back_data';                        
                    case 'world_indexes':
                        name = 'update_world_indexes';
                    break;
                }
                var $date = $("#date").val();
                updateView.openModal('Update',$html_loading,300);
                $("#modal").show(0, function(){
                    $.ajax({
                        url: $admin_url + 'update/'+name,
                        data: 'date='+$date,
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
            upload_data: function(){
                updateView.openModal('UPLOAD DATA',$html_loading,300);
                $("#modal").show(0, function(){
                    $.ajax({
                        url: $admin_url + 'update/upload_data_forday',
                        type: 'post',
                        async: false,
                        success: function(data){
                            // console.log(data);
                            var datatemplate={};
                            datatemplate.report=JSON.parse(data);
                            var compiledTemplate = _.template( reportTemplate, datatemplate );
                            $('.caculation-report').html(compiledTemplate).fadeIn();
                        }
                    });
                });
            },
            ifrclab: function(){
                updateView.openModal('IFRC LAB',$html_loading,300);
                $("#modal").show(0, function(){
                    $.ajax({
                        url: $admin_url + 'update/update_ifrc_lab',
                        type: 'post',
                        async: false,
                        success: function(data){
                            // console.log(data);
                            var datatemplate={};
                            datatemplate.report=JSON.parse(data);
                            var compiledTemplate = _.template( reportTemplate, datatemplate );
                            $('.caculation-report').html(compiledTemplate).fadeIn();
                        }
                    });
                });
            },
            get_hnx: function(){
                updateView.openModal('Download HNX',$html_loading,300);
                $("#modal").show(0, function(){
                    $.ajax({
                        url: $admin_url + 'update/update_get_hnx',
                        type: 'post',
                        async: false,
                        success: function(data){
                            // console.log(data);
                            var datatemplate={};
                            datatemplate.report=JSON.parse(data);
                            var compiledTemplate = _.template( reportTemplate, datatemplate );
                            $('.caculation-report').html(compiledTemplate).fadeIn();
                        }
                    });
                });
            },
            
            get_hsx: function(){
                updateView.openModal('Download HSX',$html_loading,300);
                $("#modal").show(0, function(){
                    $.ajax({
                        url: $admin_url + 'update/update_get_hsx',
                        type: 'post',
                        async: false,
                        success: function(data){
                            // console.log(data);
                            var datatemplate={};
                            datatemplate.report=JSON.parse(data);
                            var compiledTemplate = _.template( reportTemplate, datatemplate );
                            $('.caculation-report').html(compiledTemplate).fadeIn();
                        }
                    });
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
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return updateView = new updateView;
    });

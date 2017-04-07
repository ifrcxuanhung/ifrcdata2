define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var stepsView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){

            },

            events: {
                "click #save": "excute",
                "click #save1": "excute1",
                "click #save2": "excute2"
            },

            excute: function(e){
                $this = $(e.currentTarget);
                $("#file-daily").hide();
                stepsView.openModal('Reference Switch', $html, 400);
                var start = $("#startdate").val();
                stepsView.ref_switch(start);
            },

            ref_switch: function(start){
                $.ajax({
                    url: $admin_url + 'reference/process_check_date',
                    data: 'date='+start,
                    type: 'post',
                    async: false,
                    success: function(data){
                        var datatemplate={};
                        datatemplate.report=JSON.parse(data);
                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                        var date = datatemplate.report.date;
                        var key = datatemplate.report.key;
                        if(key != ''){
                            if(key == 'Yes'){
                                $('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>"
                                    +"<ul>"
                                    +"<li>Data of day "+date+"</li>"
                                    +"<li>Day: "+datatemplate.report.day+"</li>"
                                    +"<li>Daily: "+datatemplate.report.daily+"</li>"
                                    +"<li>History: "+datatemplate.report.history+"</li>"
                                    +"<li>Do you want continuous ?</li>"
                                    +"</ul>"
                                    +"</div>"
                                    +"<div class='button' style='width:37%'><button id='accept' style='float:left;margin-right:20%;'>Yes</button><button id='de-accept'>No</button></div>").fadeIn();
                                $('#accept').click(function(){
                                    //$('#lean_overlay').show('0',function(){
                                    $("#modal").remove();
                                    stepsView.openModal('Reference Switch', $html, 400);
                                    $("#modal").show(0, function(){
                                        $.ajax({
                                            url: $admin_url + 'reference/process_reference_switch',
                                            data: 'date=' + date + '&key=' + key,
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
                                })
                                $('#de-accept').click(function(){
                                    window.location.href = $admin_url;
                                })
                            }else{
                                $(document).ready(function(){
                                    //$('#lean_overlay').show('0',function(){
                                    stepsView.openModal('Reference Switch', $html, 400);
                                    $("#modal").show(0, function(){
                                        $.ajax({
                                            url: $admin_url + 'reference/process_reference_switch',
                                            type: 'post',
                                            data: 'date=' + date + '&key=' + key,
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
                            }
                        }else{
                            $('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>"
                                +"<ul>"
                                +"<li>Data of day "+date+"</li>"
                                +"<li>Day: "+datatemplate.report.day+"</li>"
                                +"</ul>"
                                +"</div>").fadeIn();
                        }
                    }
                });
            },

            index: function(){
                $(document).ready(function()
                {
                    console.log('index');
                });
            },

            openModal: function openModal($title,$content,$width){
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

            reference_switch: function(){
                $(document).ready(function(){
                    $("#startdate").datepicker({
                        maxDate: 0,
                        dateFormat: 'yy-mm-dd',
                        onSelect: function(selected){
                            $("#enddate").datepicker("option", "maxDate", selected);
                        }
                    });
                });
            },

            reference_all: function(){
                $(document).ready(function(){
                    $("#startdate").datepicker({
                        maxDate: 0,
                        dateFormat: 'yy-mm-dd',
                        onSelect: function(selected){
                            $("#startdate").datepicker("option", "maxDate", selected);
                        }
                    });
                });
            },

            reference_compare: function(){
                stepsView.openModal('Reference Compare', $html, 400);
                $("#modal").show(0, function(){
                    $.ajax({
                        url: $admin_url + 'reference/process_reference_compare',
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
                            }else{
                                $('.blocks-list').append("<li><span style='color:green'>Report</span>: Nothing</li>");
                            }
                        }
                    });
                });
            },

            excute2: function(e){
                $this = $(e.currentTarget);
                $("#file-daily").hide();
                stepsView.openModal('Reference All', $html, 400);
                var start = $("#startdate").val();
                $.ajax({
                    url: $admin_url + 'reference/process_check_date',
                    type: 'post',
                    data: 'date='+start,
                    async: false,
                    success: function(data){
                        var datatemplate={};
                        datatemplate.report=JSON.parse(data);
                        var compiledTemplate = _.template( reportTemplate, datatemplate );
                        var date = datatemplate.report.date;
                        var key = datatemplate.report.key;
                        if(key != ''){
                            if(key == 'Yes'){
                                $('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>Đã có dữ liệu ngày "+date+" , có muốn xóa dữ liệu cũ và ghi dữ liệu mới vào không?</div>"
                                    +"<div class='button' style='width:37%'><button id='accept' style='float:left;margin-right:20%;'>Yes</button><button id='de-accept'>No</button></div>").fadeIn();
                                $('#accept').click(function(){
                                    //$('#lean_overlay').show('0',function(){
                                    $("#modal").remove();
                                    stepsView.openModal('Reference All', $html, 400);
                                    $("#modal").show(0, function(){
                                        $.ajax({
                                            url: $admin_url + 'reference/process_reference_all',
                                            data: 'date=' + date + '&key=' + key,
                                            type: 'post',
                                            async: false,
                                            complete: function(){
                                                stepsView.ref_switch(date);
                                            }
                                        });
                                    });
                                })
                                $('#de-accept').click(function(){
                                    window.location.href = $admin_url;
                                })
                            }else{
                                $(document).ready(function(){
                                    //$('#lean_overlay').show('0',function(){
                                    stepsView.openModal('Reference All', $html, 400);
                                    $("#modal").show(0, function(){
                                        $.ajax({
                                            url: $admin_url + 'reference/process_reference_all',
                                            type: 'post',
                                            data: 'date=' + date + '&key=' + key,
                                            async: false,
                                            complete: function(){
                                                stepsView.ref_switch(date);
                                            }
                                        });
                                    });
                                });
                            }
                        }else{
                            $('.caculation-report').html("<div class='button' style='width:97%; margin-bottom:10px; font-size:17px; font-weight:bold'>"
                                +"<ul>"
                                +"<li>Data of day "+date+"</li>"
                                +"<li>Day: "+datatemplate.report.day+"</li>"
                                +"</ul>"
                                +"</div>").fadeIn();
                        }
                    }
                })
            },

            update_calendar: function(){
                $(document).ready(function(){
                    $("#startdate").datepicker({
                        maxDate: 0,
                        dateFormat: 'yy-mm-dd',
                        onSelect: function(selected){
                            $("#enddate").datepicker("option", "maxDate", selected);
                        }
                    });
                    $("#startdate").datepicker("setDate", new Date());
                });
            },

            excute1: function(e){
                $this = $(e.currentTarget);
                $("#update_calendar").hide();
                stepsView.openModal('Update Calendar', $html, 400);
                var start = $("#startdate").val();
                $.ajax({
                    url: $admin_url + 'reference/process_update_calendar',
                    data: 'date='+start,
                    type: 'post',
                    async: false,
                    success: function(rs){
                        var dataTemplate = {};
                        dataTemplate.report = JSON.parse(rs);
                        $('.caculation-report').html('<ul class="blocks-list">'
                            +"<li>"
                            +'<a href="#" class="float-left"><img width="16" height="16" src="'+$template_url+'images/icons/fugue/status.png"> '+dataTemplate.report.title+'</a>'
                            +'<ul class="tags float-right">'
                            +'<li class="tag-time">'+dataTemplate.report.time+' seconds</li>'
                            +"</ul>"
                            +"</li>"
                            +"<li>"
                            +'<a href="#" class="float-left"><img width="16" height="16" src="'+$template_url+'images/icons/fugue/status.png"> '+dataTemplate.report.task+'</a>'
                            +'<ul class="tags float-right">'
                            +'<li>'+dataTemplate.report.message+'</li>'
                            +"</ul>"
                            +"</li>"
                            +"</ul>").fadeIn();
                    }
                });
            },

            change: function(){
                if(window.confirm("Do you want to continue?")){
                    stepsView.openModal("REFERENCE CHANGE", $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'reference/change',
                            async: false,
                            success: function(rs){
                                var dataTemplate = {};
                                dataTemplate.report = JSON.parse(rs);
                                var compiledTemplate = _.template(reportTemplate, dataTemplate);
                                $('.caculation-report').html(compiledTemplate).fadeIn();
                            }
                        });
                    });
                }
            },

            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return stepsView = new stepsView;
    });

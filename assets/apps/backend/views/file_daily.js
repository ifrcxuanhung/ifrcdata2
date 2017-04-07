// Filename: views/hsx/list
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var fileListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click #save": "excute",
        
            },

            excute: function(e){
                $this = $(e.currentTarget);
                // var start = $("#startdate").val().split('/');
                // var end = $("#enddate").val().split('/');
                // start = Date.UTC(start[2], start[0], start[1]);
                // end = Date.UTC(end[2], end[0], end[1]);
                method = '';
                method = $($this).attr('method');
                if(method == ''){
                    method = 'index';
                }
                $("#file-daily").hide();
                $(".disable-form").show();
                $(".disable-form").append("<img src='" + $base_url + "assets/templates/backend/images/mask-loader.gif' style='position: absolute; top: 50%; left: 50%;' />");
                //$(".progress-my").progressbar({value: 50});
                var start = $("#startdate").val();
                var end = $("#enddate").val();
                //while(Date.UTC($start) <= Date.UTC($end)){
                    $.ajax({
                        url: $admin_url + "file_daily/" + method,
                        type: 'post',
                        data: 'start=' + start + '&end=' + end,
                        async: false,
                        success: function(rs){
                            alert("Finish!");
                            window.location.href = $admin_url + 'file_daily/' + method;
                        }
                    });
                //}
            },
            index: function(){
                $(document).ready(function(){
                    $("#startdate").datepicker({
                        maxDate: 0,
                        dateFormat: 'yy-mm-dd',
                        onSelect: function(selected){
                            $("#enddate").datepicker("option", "minDate", selected);
                            $("#enddate").val($("#startdate").val());
                        }
                    });
                    $("#enddate").datepicker({
                        maxDate: 0,
                        dateFormat: 'yy-mm-dd',
                        onSelect: function(selected){
                            $("#startdate").datepicker("option", "maxDate", selected);
                        }
                    });

                    
                });
            },

            ref: function(){
                fileListView.index();
            },

            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return fileListView = new fileListView;
    });

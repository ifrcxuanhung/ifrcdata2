// Filename: views/hsx/list
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var hsxListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click #save": "downloadHsx",
        
            },

            downloadHsx: function(){
                // var start = $("#startdate").val().split('/');
                // var end = $("#enddate").val().split('/');
                // start = Date.UTC(start[2], start[0], start[1]);
                // end = Date.UTC(end[2], end[0], end[1]);
                $("#custom-hsx").hide();
                $(".disable-form").show();
                $(".disable-form").append("<img src='" + $base_url + "assets/templates/backend/images/mask-loader.gif' style='position: absolute; top: 50%; left: 50%;' />");
                //$(".progress-my").progressbar({value: 50});
                var start = $("#startdate").val();
                var end = $("#enddate").val();
                //while(Date.UTC($start) <= Date.UTC($end)){
                    $.ajax({
                        url: $admin_url + "hsx/custom_hsx",
                        type: 'post',
                        data: 'start=' + start + '&end=' + end,
                        async: false,
                        success: function(rs){
                            alert("Finish!");
                            window.location.href = $admin_url + 'hsx/custom_hsx';
                        }
                    });
                //}
            },
            custom_hsx: function(){
                $(document).ready(function(){
                    $("#startdate").datepicker({
                        maxDate: 0,
                        onSelect: function(selected){
                            $("#enddate").datepicker("option", "minDate", selected);
                            $("#enddate").val($("#startdate").val());
                        }
                    });
                    $("#enddate").datepicker({
                        maxDate: 0,
                        onSelect: function(selected){
                            $("#startdate").datepicker("option", "maxDate", selected);
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
        return hsxListView = new hsxListView;
    });

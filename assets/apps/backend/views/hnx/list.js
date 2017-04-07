// Filename: views/hnx/list
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var hnxListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click #save": "downloadHnx",
        
            },

            downloadHnx: function(){
                $("#custom-hnx").hide();
                $(".disable-form").show();
                $(".disable-form").append("<img src='" + $base_url + "assets/templates/backend/images/mask-loader.gif' style='position: absolute; top: 50%; left: 50%;' />");
                var start = $("#startdate").val();
                var end = $("#enddate").val();
                    $.ajax({
                        url: $admin_url + "hnx/custom_hnx",
                        type: 'post',
                        data: 'start=' + start + '&end=' + end,
                        async: false,
                        success: function(rs){
                            console.log(rs);
                            alert("Finish!");
                            window.location.href = $admin_url + 'hnx/custom_hnx';
                        }
                    });
                //}
            },
            custom_hnx: function(){
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
        return hnxListView = new hnxListView;
    });

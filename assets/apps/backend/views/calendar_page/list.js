// Filename: views/calendar_page/index
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var calendarPageView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                //"click a.add-event": "addEvent",
            },
            addEvent: function(){
                //do something
            },
            index: function(){
                $(document).ready(function(){
                    $.ajax({
                        url: $admin_url + 'calendar_page/listCurrDate',
                        type: 'get',
                        async: false,
                        success: function(rs){
                            console.log(rs);
                            rs = JSON.parse(rs);
                            $(rs).each(function(i){
                                var $date = "#" + rs[i];
                                if($($date).length != 0){
                                    $($date).append('<ul class="dot-events"><li></li></ul>');
                                }
                            });
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
        return new calendarPageView;
    });

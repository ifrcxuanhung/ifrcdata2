// Filename: views/download_histoday/list
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var downloadHistodayListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click .submit": "excute",
        
            },

            excute: function(){
                var options;
                request = {
                    'code_dwl': $("#code_dwl").val()
                };
                if(request.code_dwl == 0){
                    request.code_info = $("#code_info").val();
                    request.market = $("#market").val();
                    request.url = $("#url").val();
                    request.input = $("#input").val();
                    request.time = $("#time").val();
                }
                // request = JSON.stringify(request);
                $.ajax({
                    url: $admin_url + 'download_histoday/getOptions',
                    type: 'post',
                    data: request,
                    async: false,
                    success: function(rs){
                        if(rs == 0){
                            alert('No suitable data in database');
                        }else{
                            options = JSON.parse(rs);
                        }
                    }
                });
                $(options).each(function(i){
                    for(var j = 0; j <= options[i].multipages; j++){
                        $.ajax({
                            url: $admin_url + 'download_histoday/getData',
                            type: 'post',
                            data: {options: options[i], page: j},
                            async: false,
                            success: function(rs){
                                //
                            }
                        });
                    }
                });
                
            },

            index: function(){
            },

            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return downloadHistodayListView = new downloadHistodayListView;
    });

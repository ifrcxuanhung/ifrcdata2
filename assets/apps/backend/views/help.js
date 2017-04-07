// Filename: views/projects/list
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var helpDetailView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click li.article-detail": "doShowDetailHelp"
            },
            doShowDetailHelp:function(event){
                var $this=$(event.currentTarget);
                var id = $this.val();
                var anchor = $this;
                if (anchor.data("disabled")) {
                    return false;
                }
                anchor.data("disabled", "disabled");
                $("#loading").show();
                $(".grid_8").hide();
                $.ajax({
                    url: $admin_url + 'help',
                    type: 'POST',
                    data: 'id=' + id,
                    success: function(response){
                        var $data=JSON.parse(response);
                        //console.log($data);
                        if(typeof $data.article_description !== "undefined") {
                            var $html='<section class="grid_8">'+
                            '<div id="content" class="block-border">'+
                            '<div class="block-content upper-index no-padding">'+
                            '<div class="h1">'+
                            '<h1>'+$data.name_cate[0]['name']+'</h1>'+
                            '</div>'+
                            '<div class="block-controls">'+
                            '<ul class="controls-buttons">'+
                            '<li>'+
                            '<br>'+
                            '</li>'+
                            '</ul>'+
                            '</div>'+
                            '</div>'+
                            '<div class="block-content no-title fix_help">'+
                            '<h2 class="bigger">'+$data.article_description[0]['title']+'</h2>'+
                            '<p>'+$data.article_description[0]['description']+'</p>'+
                            '<p>'+$data.article_description[0]['long_description']+'</p>'+
                            '</div>'+
                            '</div>'+
                            '</section>';
                            $('#reponse-detail').html($html);
                            $(".grid_8").show();
                        }
                        anchor.removeData("disabled");
                        $("#loading").hide();
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
        return helpDetailView = new helpDetailView;
    });

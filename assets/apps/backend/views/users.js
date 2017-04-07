// Filename: views/projects/list
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var userListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click a.action-delete": "doDelete"
            },
            doDelete:function(event){
                var $this=$(event.currentTarget);
                if (confirm('Are you sure')) {
                    var id=$($this).attr('user_id');
                    $.ajax({
                        type: "POST",
                        url: $admin_url+"users/delete",
                        data: "id="+id,
                        success: function(msg){
                            if(msg>=1){
                                $($this).parents('tr').fadeOut('slow');
                            }
                        }
                    });
                }
            },
            index: function(){
                $(document).ready(function()
                {
                    oTable = $('.table-user-list').dataTable({
                        "iDisplayLength":10,
                        "iDisplayStart": 0,
                        "bProcessing": true,
                        "aaSorting": [],
                        "sAjaxSource": $admin_url+"users/listdata",
                        "aoColumns": [
                        {
                            "mData": "id"
                        },
                        {
                            "mData": "first_name"
                        },
                        {
                            "mData": "last_name"
                        },
                        {
                            "mData": "email"
                        },
                        {
                            "mData": "group"
                        },
                        {
                            "mData": "active"
                        },
                        {
                            "mData": "action"
                        }
                        ],
                        "sPaginationType": "full_numbers",
                        sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',

                        /* Callback to apply template setup*/
                        fnDrawCallback: function()
                        {
                            this.parent().applyTemplateSetup();
                            $(this).slideDown(200);
                        },
                        fnInitComplete: function()
                        {
                            this.parent().applyTemplateSetup();
                            $(this).slideDown(200);
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
        return new userListView;
    });

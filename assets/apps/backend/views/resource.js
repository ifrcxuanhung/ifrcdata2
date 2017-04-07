// Filename: views/projects/list
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var resourceListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click a.action-delete": "doDelete"
            },
            doDelete:function(event){
                var $this=$(event.currentTarget);
                if (confirm('Are you sure')) {
                    var id=$($this).attr("resource_id");
                    $.ajax({
                        type: "POST",
                        url: $admin_url+"resource/delete",
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
                    oTable = $('.table-resource-list').dataTable({
                        "iDisplayLength":10,
                        "iDisplayStart": 0,
                        "bProcessing": true,
                        "aaSorting": [],
                        "sAjaxSource": $admin_url+"resource/listdata",
                        "aoColumns": [
                        {
                            "mData": "resource_id"
                        },
                        {
                            "mData": "module"
                        },
                        {
                            "mData": "controller"
                        },
                        {
                            "mData": "action"
                        },
                        {
                            "mData": "actions"
                        },
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
        return new resourceListView;
    });

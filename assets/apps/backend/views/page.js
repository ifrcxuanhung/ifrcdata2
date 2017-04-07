// Filename: views/projects/list
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var pageListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click a.action-delete": "doDelete"
            },
            doDelete:function(event){
                var $this=$(event.currentTarget);
                if (confirm('Are you sure')) {
                    var id=$($this).attr('page_id');
                    $.ajax({
                        type: "POST",
                        url: $admin_url+"page/delete",
                        data: "id="+id,
                        success: function(msg){
                            if(msg == "success"){
                                $($this).parents('tr').fadeOut('slow');
                            }
                        }
                    });
                }
            },
            index: function(){
                $(document).ready(function()
                {
                    if(check_file_exists($base_url + 'assets/language/datatables/' + $lang + '.txt')){
                        $file = $base_url + 'assets/language/datatables/' + $lang + '.txt';
                    }else{
                        $file = $base_url + 'assets/language/datatables/eng.txt';
                    }
                    oTable = $('.table-page-list').dataTable({
                        "oLanguage":{
                            "sUrl": $file
                        },
                        "iDisplayLength":10,
                        "iDisplayStart": 0,
                        "bProcessing": true,
                        "aaSorting": [],
                        "sAjaxSource": $admin_url+"page/listdata",
                        "aoColumns": [
                        {
                            "mData": "id"
                        },
                        {
                            "mData": "name"
                        },
                        {
                            "mData": "layout_id"
                        },
                        {
                            "mData": "code"
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
        return new pageListView;
    });

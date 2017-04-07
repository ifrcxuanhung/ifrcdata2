define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var compareView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            // openModal('Update Indexes', $html, 400);
            },
            events: {

            },

            excute: function(e){

            },
            index: function(){
                $(document).ready(function()
                {
                    if (typeof oTable != "undefined") {
                        $("#table_compare").dataTable().fnDestroy();
                    }
                    if (check_file_exists($base_url + 'assets/language/datatables/' + $lang + '.txt')) {
                        $file = $base_url + 'assets/language/datatables/' + $lang + '.txt';
                    } else {
                        $file = $base_url + 'assets/language/datatables/eng.txt';
                    }
                    var $url = document.URL;
                    oTable = $('#table_compare').dataTable({
                        "oLanguage": {
                            "sUrl": $file
                        },
                        "sScrollY": '280px',
                        "bScrollCollapse": true,
                        "bPaginate": false,
                        "iDisplayLength": 10,
                        "iDisplayStart": 0,
                        "bProcessing": true,
                        "aaSorting": [],
                        "sAjaxSource": $url,
                        "aoColumns": [
                        {
                            "mData": "ticker"
                        },
                        {
                            "mData": "date"
                        },
                        {
                            "mData": "dividend",
                            "sType": "formatted-num"
                        },
                        {
                            "mData": "ticker_currency"
                        },
                        {
                            "mData": "date_currency"
                        },
                        {
                            "mData": "dividend_currency",
                            "sType": "formatted-num"
                        },
                        {
                            "mData": "status"
                        }
                        ],
                        "sPaginationType": "full_numbers",
                        sDom: '<"block-controls"<"controls-buttons"p>f>rti',
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
                            $(".block-controls").prepend($html);
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
        return compareView = new compareView;
    });

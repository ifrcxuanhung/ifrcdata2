// Filename: views/hnx/list
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var vndbReportListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click #save": "count"

            },
            count: function(){
                start = $("#startdate").val();
                end = $("#enddate").val();
                if(check_file_exists($base_url + 'assets/language/datatables/' + $lang + '.txt')){
                    $file = $base_url + 'assets/language/datatables/' + $lang + '.txt';
                }else{
                    $file = $base_url + 'assets/language/datatables/eng.txt';
                }
                if(typeof oTable != 'undefined'){
                    oTable.fnDestroy();
                }
                oTable = $('.table-report').dataTable({
                    "oLanguage":{
                        "sUrl": $file
                    },
                    //"bRetrieve": true,
                    "iDisplayLength":10,
                    "iDisplayStart": 0,
                    "bProcessing": true,
                    "bRetrieve": true,
                    "aaSorting": [],
                    "bAutoWidth": true,
                    "bServerSide": true,
                    "sAjaxSource": $admin_url + 'vndb_report',
                    "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                        aoData.push({
                            "name" : "start",
                            "value" : start
                        }, {
                            "name": "end",
                            "value": end
                        });
                        // $.getJSON( sSource, aoData, function(json) {
                        //              fnCallback(json)
                        //      });
                        $.ajax( {
                            "dataType": 'json',
                            "type": "POST",
                            "url": sSource,
                            "data": aoData,
                            "success": fnCallback
                        });

                    },
                    "aoColumns": [
                    {
                        "mData": "market",
                        "sType": "string",
                        "swidth": "25%"
                    },
                    {
                        "mData": "row",
                        "sType": "number",
                        "swidth": "25%"
                    },
                    {
                        "mData": "last",
                        "sType": "number",
                        "swidth": "25%"
                    },
                    {
                        "mData": "shli",
                        "sType": "number",
                        "swidth": "25%"
                    },
                    {
                        "mData": "shou",
                        "sType": "number",
                        "swidth": "25%"
                    },
                    {
                        "mData": "adj_cls",
                        "sType": "number",
                        "swidth": "25%"
                    },
                    ],
                    //"sPaginationType": "full_numbers",
                    sDom: '<"block-controls"<"controls-buttons"p>f>rti<"block-footer clearfix"l>',

                    /* Callback to apply template setup*/
                    fnDrawCallback: function()
                    {
                        // this.parent().applyTemplateSetup();
                        $(this).slideDown(200);
                    },
                    fnInitComplete: function()
                    {
                        // this.parent().applyTemplateSetup();
                        $(this).slideDown(200);
                        $(".table-report").siblings(".block-controls").children().remove();
                        $(".table-report").siblings(".block-footer").remove();
                        $(".table-report").siblings(".message").remove();

                    }
                });
                if(typeof oTable3 != 'undefined'){
                    oTable.fnDestroy();
                }
                oTable3 = $('.table-shares').dataTable({
                    "oLanguage":{
                        "sUrl": $file
                    },
                    //"bRetrieve": true,
                    "iDisplayLength":10,
                    "iDisplayStart": 0,
                    "bProcessing": true,

                    "aaSorting": [],
                    "bAutoWidth": true,

                    "sAjaxSource": $admin_url + 'vndb_report/checkShares',
                    "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                        aoData.push({
                            "name" : "start",
                            "value" : start
                        }, {
                            "name": "end",
                            "value": end
                        });
                        $.ajax( {
                            "dataType": 'json',
                            "type": "POST",
                            "url": sSource,
                            "data": aoData,
                            "success": fnCallback
                        });

                    },
                    "aoColumns": [
                    {
                        "mData": "date",
                        "sType": "string",
                        "swidth": "20%"
                    },
                    {
                        "mData": "ticker",
                        "sType": "string",
                        "swidth": "20%"
                    },
                    {
                        "mData": "market",
                        "sType": "string",
                        "swidth": "20%"
                    },
                    {
                        "mData": "shli_change",
                        "sType": "string",
                        "swidth": "20%"
                    },
                    {
                        "mData": "shou_change",
                        "sType": "string",
                        "swidth": "20%"
                    },
                    ],
                    "sPaginationType": "full_numbers",
                    sDom: '<"block-controls"<"controls-buttons"p>f>rti<"block-footer clearfix"l>',

                    /* Callback to apply template setup*/
                    fnDrawCallback: function()
                    {
                        // this.parent().applyTemplateSetup();
                        $(this).slideDown(200);
                    },
                    fnInitComplete: function()
                    {

                    }
                });
            },
            index: function(){
                $(document).ready(function(){
                    var $date='';
                    $.ajax({
                        type: "POST",
                        url: $admin_url+"vndb_report/date",
                        success: function(data){
                            data=JSON.parse(data);
                            $date=data.date;
                            $("#startdate").datepicker({
                                maxDate: 0,
                                minDate:$date,
                                dateFormat: 'yy-mm-dd',
                                onSelect: function(selected){
                                    $("#enddate").datepicker("option", "minDate", selected);
                                    $("#enddate").val($("#startdate").val());
                                }
                            });
                            $("#enddate").datepicker({
                                maxDate: 0,
                                minDate:$date,
                                dateFormat: 'yy-mm-dd',
                                onSelect: function(selected){
                                    $("#startdate").datepicker("option", "maxDate", selected);
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
        return vndbReportListView = new vndbReportListView;
    });

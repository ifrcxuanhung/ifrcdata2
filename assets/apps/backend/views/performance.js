define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
    ], function($, _, Backbone,reportTemplate){
        var $html='<div class="caculation-report"><img style="margin-left:180px" src="'+$template_url+'images/loading.gif"/></div>';
        var performanceView = Backbone.View.extend({
            el: $(".main-container"),

            initialize: function(){

            },

            events: {
                "click .excute": "excute_chart",
            },

            excute: function(e){
                e.stopImmediatePropagation();
                e.preventDefault();
                $this = $(e.currentTarget);
            },

            openModal: function openModal($title,$content,$width)
            {
                $.modal({
                    content: $content,
                    title: $title,
                    maxWidth: 2500,
                    width: $width,
                    buttons: {
                        'Close': function(win) {
                            window.location.href = $admin_url;
                        }
                    }
                });
            },

            update_month_year: function(){
                $(document).ready(function(){
                    performanceView.openModal('Check Table', $html, 400);
                    $("#modal").show(0, function(){
                        $.ajax({
                            url: $admin_url + 'performance/process_check_table_exists',
                            type: 'post',
                            async: false,
                            success: function(rs){
                                rs = JSON.parse(rs);
                                var $table_not_exists = [];
                                var $table_exists = [];
                                $.each( rs.check_table, function( key, value ) {
                                    if(value.exists == 0){
                                        $table_not_exists.push(value.table+'|'+value.connected);
                                    }else{
                                        $table_exists.push(value.table+'|'+value.connected);
                                    }
                                });
                                if($table_not_exists.length == 0){
                                    $("#modal").remove();
                                    performanceView.openModal('Update Index', $html, 400);
                                    $("#modal").show(0, function(){
                                        $.ajax({
                                            url: $admin_url + 'performance/process_update_month_year',
                                            data: 'data_table='+$table_exists,
                                            type: 'post',
                                            async: false,
                                            success: function(data){
                                                var datatemplate={};
                                                datatemplate.report=JSON.parse(data);
                                                var compiledTemplate = _.template( reportTemplate, datatemplate );
                                                $('.caculation-report').html(compiledTemplate).fadeIn();
                                            }
                                        });
                                    });
                                }else{
                                    $html = '<ul>';
                                    $.each( $table_not_exists, function( key, value ) {
                                        var arr_value = value.split('|');
                                        $html += '<li style="margin-bottom:10px">Table <span style="font-weight:bold">'+arr_value[0]+'</span> of host <span style="font-weight:bold">'+arr_value[1]+'</span> does\'t exists</li>';
                                    });
                                    $html += '</ul>';
                                    $("#modal").remove();
                                    performanceView.openModal('An Error Occurred', $html, 400);
                                }
                            }
                        });
                    })
                });
            },
            chart :function(){
                var pathname = window.location.href;
                pathname = pathname.split("#&");
                window.history.pushState("", "", pathname[0]);
            },

            excute_chart : function(){
                var $table = 'q_index_vnx_daily';
                $("#tab_chart").show();
                var code_mother = $('select[name="code_mother"] option:selected').val();
                var currency = $('select[name="currency"] option:selected').val();
                var type = $('select[name="type"] option:selected').val();

                var $data_filter = $(".current").find('a').attr('href');
                var $filter = $data_filter.replace("#", "");

                switch($filter)
                {
                case "month":
                    $table = 'q_index_vnx_monthly';
                    break;
                case "year":
                    $table = 'q_index_vnx_yearly';
                    break;
                default:
                    $table = 'q_index_vnx_daily';
                }

                performanceView.table_filter($table, code_mother, currency, type);
                performanceView.chart_filter($table, code_mother, currency, type);

                $(".get_table").on("click", function() {
                    var code_mother = $('select[name="code_mother"] option:selected').val();
                    var currency = $('select[name="currency"] option:selected').val();
                    var type = $('select[name="type"] option:selected').val();
                    var $table = $(this).attr("id");
                    performanceView.table_filter($table, code_mother, currency, type);
                    performanceView.chart_filter($table, code_mother, currency, type);
                });
            },

            table_filter: function($table, $code_mother, $currency, $type) {
                if (typeof oTable != "undefined") {
                    $("#tab_" + $table).dataTable().fnDestroy();
                }
                oTable = $("#tab_" + $table).dataTable({
                    "aaSorting": [],
                    "sAjaxSource": $admin_url + "performance/get_table",
                    "fnServerData": function(sSource, aaData, fnCallback, oSetting) {
                        aaData.push(
                            {
                                name: 'table',
                                value: $table
                            },
                            {
                                name: 'code_mother',
                                value: $code_mother
                            },
                            {
                                name: 'currency',
                                value: $currency
                            },
                            {
                                name: 'type',
                                value: $type
                            }
                        );
                        $.ajax({
                            dataType: "JSON",
                            url: sSource,
                            type: "POST",
                            data: aaData,
                            success: function(rs) {
                                data = rs;
                                $("#lean_overlay").hide();
                                fnCallback(rs);
                            }
                        });
                    },
                    "aoColumns": [
                        {
                            "mData": "idx_code_mother",
                            "sType": "string",
                            "sClass": "string"
                        },
                        {
                            "mData": "date",
                            "sType": "string",
                            "sClass": "string"
                        },
                        {
                            "mData": "close",
                            "sType": "numeric",
                            "sClass": "numeric"
                        },
                        {
                            "mData": "perform",
                            "sType": "numeric",
                            "sClass": "numeric"
                        }
                    ],
                    "bRetrieve": true,
                    "scrollable": true,
                    "bPaginate": false,
                    "sScrollY": "240px",
                    "bScrollCollapse": true,
                    "bProcessing": true,
                    "bServerSide": true,
                    "iDisplayLength": 10,
                    "iDisplayStart": 0,
                    sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
                    fnDrawCallback: function()
                    {
                        /* this.parent().applyTemplateSetup(); */
                    },
                    fnInitComplete: function()
                    {
                        $(this).slideDown(200);
                        /* this.parent().applyTemplateSetup(); */
                    }
                });
            },
            chart_filter: function($table, $code_mother, $currency, $type) {
                $.ajax({
                    url: $admin_url + 'performance/get_chart',
                    type: 'post',
                    data: 'table=' + $table + '&code_mother=' + $code_mother + '&currency=' + $currency + '&type=' + $type,
                    async: false,
                    success: function(data) {
                        data = JSON.parse(data);
                        $('#container').highcharts('StockChart', {
                            chart: {
                                height: '450',
                                type: 'line'
                            },
                            credits: {
                                enabled: false
                            },
                            rangeSelector: {
                                enabled: false,
                                selected: 1
                            },
                            title: {
                                text: $code_mother+$type+$currency
                            },
                            tooltip: {
                                style: {
                                    width: '200px'
                                },
                                valueDecimals: 4
                            },
                            xAxis: {
                                type: 'date'
                            },
                            yAxis: {
                                title: {
                                    text: 'Close'
                                }
                            },
                            exporting: {
                                buttons: {
                                    contextButton: {
                                        menuItems: null,
                                        onclick: function() {
                                            this.exportChart();
                                        }
                                    }
                                }
                            },
                            series: [{
                                name: $code_mother+$type+$currency,
                                data: data,
                                id: 'dataseries'
                            }]
                        });
                    }
                });
            },

            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }

        });
        return performanceView = new performanceView;
    });

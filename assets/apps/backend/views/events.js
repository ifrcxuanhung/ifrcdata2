// Filename: views/events
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var eventsListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click button.import": "import",
                "keypress #custom-date": "showCustomDate",
                "click #btn-cancel": "cancel",
                "click #btn-back": "back",
                "click input[type='checkbox']": "getCheckDate",
                "click .export-buttons ul li a": "goTo",
                "click .my-buttons div button": "goTo",
                "click a.view-more": "viewMore"
            },
            index:function(){
                $("#event-dialog").dialog({
                    modal: true,
                    autoOpen: false,
                    closeOnEscape: true,
                    width: 500
                });
                if(check_file_exists($base_url + 'assets/language/datatables/' + $lang + '.txt')){
                    $file = $base_url + 'assets/language/datatables/' + $lang + '.txt';
                }else{
                    $file = $base_url + 'assets/language/datatables/eng.txt';
                }
                if(typeof oTable != 'undefined'){
                    $("#vndb_events_day").dataTable().fnDestroy();
                }
                var url = location.href;
                $.ajax({
                    url: $admin_url + 'events/get_type',
                    async: false,
                    success: function(rs){
                        var $data_type = JSON.parse(rs);
                        oTable = $('.table-events-list').dataTable({
                            "oLanguage":{
                                "sUrl": $file
                            },
                            "sScrollY": "370px",
                            // "sScrollXInner": "110%",
                            "sScrollX": "100%", /* Required for viewing tables with lots of columns at low resolution - otherwise columns are mis-aligned */
                            "bScrollCollapse": true,
                            "iDisplayLength": 10,
                            "iDisplayStart": 0,
                            "bProcessing": true,
                            "bPaginate": false,
                            "bRetrieve": true,
                            "aaSorting": [],
                            "bAutoWidth": true,
                            "bServerSide": true,
                            "sAjaxSource": url,
                            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                                $.ajax( {
                                    "dataType": 'json',
                                    "type": "POST",
                                    "url": sSource,
                                    "data": aoData,
                                    success: function(rs){
                                        fnCallback(rs);
                                    }
                                });
                            },
                            "aoColumns": [
                                {
                                    "mData": "ticker",
                                    "sType": "string",
                                    "sWidth": "2%",
                                    "sClass": "string",
                                    "sName": "ticker"
                                },
                                {
                                    "mData": "market",
                                    "sType": "string",
                                    "sWidth": "2%",
                                    "sClass": "string",
                                    "sName": "market"
                                },
                                {
                                    "mData": "date_ann",
                                    "sType": "string",
                                    "sWidth": "5%",
                                    "sClass": "string",
                                    "sName": "date_ann"
                                },
                                {
                                    "mData": "event_type",
                                    "sType": "string",
                                    "sWidth": "10%",
                                    "sClass": "string",
                                    "sName": "event_type"
                                },
                                {
                                    "mData": "evname",
                                    "sType": "numeric",
                                    "sWidth": "25%",
                                    "sClass": "string",
                                    "sName": "evname"
                                },
                                {
                                    "mData": "content",
                                    "sType": "string",
                                    "sWidth": "20%",
                                    "sClass": "string",
                                    "sName": "content"
                                },
                                {
                                    "mData": "confirm",
                                    "sType": "string",
                                    "sWidth": "2%",
                                    "sClass": "string",
                                    "sName": "confirm"
                                },
                                {
                                    "mData": "action",
                                    "sType": "string",
                                    "swidth": "2%",
                                    "sClass": "string"
                                }
                            ],
                            "sPaginationType": "full_numbers",
                            //sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
                            sDom: '<"block-controls"<"export-buttons"><"controls-buttons"p><"my-buttons">>rti<"block-footer clearfix"<"my-buttons2">lf>',

                            /* Callback to apply template setup*/
                            fnDrawCallback: function(){
                                $(this).slideDown(200);
                                $(".with-tip").tip();
                                var html = '<div style="float: right; margin-top:-5px">' +
                                                    '<button action="history" class="with-tip" type="button">' + trans('bt_history') + '</button> ' +
                                                    '<button action="today" class="with-tip red" type="button">' + trans('bt_today')  + '</button> ' +
                                                '</div>' +
                                                '<div style="clear: left;"></div>';
                                $(".my-buttons").html(html);
                                html = '<div style="float: left; margin-top:0px">'+
                                        '<ul>';
                                        var $count = $data_type.length;
                                        var i = 0;
                                        $.each($data_type, function(k, type){
                                            i++;
                                            var filter = '';
                                            if(i != $count){    
                                                filter = ' |&nbsp;';
                                            }
                                            html += '<li style="width:auto; float:left"><a href="'+$admin_url+'events/index?type='+type.evname_en.replace(" ", "+")+'">'+type.evname_en+'</a>'+filter+'</li>';
                                        });
                                html += '</ul></div>'+
                                        '<div style="clear: left;"></div>';
                                $(".my-buttons2").css({"width":"80%","float":"left"}).html(html);
                                oTable.fnSetFilteringPressEnter();
                            },
                            fnInitComplete: function(){
                                $(this).slideDown(200);
                            }
                        });  
                    }
                })
            },
            prepare:function(){
                $("#event-dialog").dialog({
                    modal: true,
                    autoOpen: false,
                    closeOnEscape: true,
                    width: 500
                });
                if(check_file_exists($base_url + 'assets/language/datatables/' + $lang + '.txt')){
                    $file = $base_url + 'assets/language/datatables/' + $lang + '.txt';
                }else{
                    $file = $base_url + 'assets/language/datatables/eng.txt';
                }
                if(typeof oTable != 'undefined'){
                    $("#vndb_events_day").dataTable().fnDestroy();
                }
                var url = location.href;
                oTable = $('.table-events-list').dataTable({
                    "oLanguage":{
                        "sUrl": $file
                    },
                    "sScrollY": "370px",
                    // "sScrollXInner": "110%",
                    "bScrollCollapse": true,
                    "iDisplayLength": 10,
                    "iDisplayStart": 0,
                    "bProcessing": true,
                    "bPaginate": false,
                    "bRetrieve": true,
                    "aaSorting": [],
                    "bAutoWidth": true,
                    "bServerSide": true,
                    "sAjaxSource": url,
                    "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                        $.ajax( {
                            "dataType": 'json',
                            "type": "POST",
                            "url": sSource,
                            "data": aoData,
                            success: function(rs){
                                fnCallback(rs);
                            }
                        });
                    },
                    "aoColumns": [
                        {
                            "mData": "ticker",
                            "sType": "string",
                            "sWidth": "2%",
                            "sClass": "string",
                            "sName": "ticker"
                        },
                        {
                            "mData": "market",
                            "sType": "string",
                            "sWidth": "2%",
                            "sClass": "string",
                            "sName": "market"
                        },
                        {
                            "mData": "date_ann",
                            "sType": "string",
                            "sWidth": "5%",
                            "sClass": "string",
                            "sName": "date_ann"
                        },
                        {
                            "mData": "event_type",
                            "sType": "string",
                            "sWidth": "10%",
                            "sClass": "string",
                            "sName": "event_type"
                        },
                        {
                            "mData": "evname",
                            "sType": "numeric",
                            "sWidth": "25%",
                            "sClass": "string",
                            "sName": "evname"
                        },
                        {
                            "mData": "content",
                            "sType": "string",
                            "sWidth": "20%",
                            "sClass": "string",
                            "sName": "content"
                        },
                        {
                            "mData": "confirm",
                            "sType": "string",
                            "sWidth": "2%",
                            "sClass": "string",
                            "sName": "confirm"
                        },
                        {
                            "mData": "action",
                            "sType": "string",
                            "swidth": "2%",
                            "sClass": "string"
                        }
                    ],
                    "sPaginationType": "full_numbers",
                    //sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
                    sDom: '<"block-controls"<"export-buttons"><"controls-buttons"p><"my-buttons">>rti<"block-footer clearfix"lf>',

                    /* Callback to apply template setup*/
                    fnDrawCallback: function(){
                        $(this).slideDown(200);
                        $(".with-tip").tip();
                        var html = '<div style="float: right; margin-top:-5px">' +
                                            '<button action="history" class="with-tip" type="button">' + trans('bt_history') + '</button> ' +
                                            '<button action="today" class="with-tip red" type="button">' + trans('bt_today')  + '</button> ' +
                                        '</div>' +
                                        '<div style="clear: left;"></div>';
                        $(".my-buttons").html(html);
                        oTable.fnSetFilteringPressEnter();
                    },
                    fnInitComplete: function(){
                        $(this).slideDown(200);
                    }
                });
            },
            view:function(){
                $("#event-dialog").dialog({
                    modal: true,
                    autoOpen: false,
                    closeOnEscape: true,
                    width: 500
                });
                if(check_file_exists($base_url + 'assets/language/datatables/' + $lang + '.txt')){
                    $file = $base_url + 'assets/language/datatables/' + $lang + '.txt';
                }else{
                    $file = $base_url + 'assets/language/datatables/eng.txt';
                }
                if(typeof oTable != 'undefined'){
                    $("#vndb_ca_daily").dataTable().fnDestroy();
                }
                var url = location.href;
                oTable = $('.table-events-list').dataTable({
                    "oLanguage":{
                        "sUrl": $file
                    },
                    "sScrollY": "370px",
                    "sScrollX": "100%",
                    "sScrollXInner": "150%",
                    "bScrollCollapse": true,
                    "iDisplayLength": 10,
                    "iDisplayStart": 0,
                    "bProcessing": true,
                    "bPaginate": false,
                    "bRetrieve": true,
                    "aaSorting": [],
                    "bAutoWidth": true,
                    "bServerSide": true,
                    "sAjaxSource": url,
                    "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                        $.ajax( {
                            "dataType": 'json',
                            "type": "POST",
                            "url": sSource,
                            "data": aoData,
                            success: function(rs){
                                fnCallback(rs);
                            }
                        });
                    },
                    "aoColumns": [
                        {
                            "mData": "ticker",
                            "sType": "string",
                            "sWidth": "2%",
                            "sClass": "string",
                            "sName": "ticker"
                        },
                        {
                            "mData": "market",
                            "sType": "string",
                            "sWidth": "2%",
                            "sClass": "string",
                            "sName": "market"
                        },
                        {
                            "mData": "events_type",
                            "sType": "string",
                            "sWidth": "5%",
                            "sClass": "string",
                            "sName": "events_type"
                        },
                        {
                            "mData": "date_ann",
                            "sType": "string",
                            "sWidth": "3%",
                            "sClass": "string",
                            "sName": "date_ann"
                        },
                        {
                            "mData": "sh_old",
                            "sType": "numeric",
                            "sWidth": "3%",
                            "sClass": "numeric",
                            "sName": "sh_old"
                        },
                        {
                            "mData": "sh_add",
                            "sType": "numeric",
                            "sWidth": "3%",
                            "sClass": "numeric",
                            "sName": "sh_add"
                        },
                        {
                            "mData": "sh_new",
                            "sType": "numeric",
                            "sWidth": "3%",
                            "sClass": "numeric",
                            "sName": "sh_new"
                        },
                        {
                            "mData": "sh_type",
                            "sType": "string",
                            "sWidth": "2%",
                            "sClass": "string",
                            "sName": "sh_type"
                        },
                        {
                            "mData": "ipo_date",
                            "sType": "string",
                            "sWidth": "3%",
                            "sClass": "string",
                            "sName": "ipo_date"
                        },
                        {
                            "mData": "ftrd",
                            "sType": "string",
                            "sWidth": "3%",
                            "sClass": "string",
                            "sName": "ftrd"
                        },
                        {
                            "mData": "date_ex",
                            "sType": "string",
                            "sWidth": "3%",
                            "sClass": "string",
                            "sName": "date_ex"
                        },
                        {
                            "mData": "date_rec",
                            "sType": "string",
                            "sWidth": "3%",
                            "sClass": "string",
                            "sName": "date_rec"
                        },
                        {
                            "mData": "date_pay",
                            "sType": "string",
                            "sWidth": "3%",
                            "sClass": "string",
                            "sName": "date_pay"
                        },
                        {
                            "mData": "ratio",
                            "sType": "numeric",
                            "sWidth": "2%",
                            "sClass": "numeric",
                            "sName": "ratio"
                        },
                        {
                            "mData": "year",
                            "sType": "numeric",
                            "sWidth": "2%",
                            "sClass": "numeric",
                            "sName": "year"
                        },
                        {
                            "mData": "period",
                            "sType": "numeric",
                            "sWidth": "2%",
                            "sClass": "numeric",
                            "sName": "period"
                        },
                        {
                            "mData": "prices",
                            "sType": "numeric",
                            "sWidth": "2%",
                            "sClass": "numeric",
                            "sName": "prices"
                        },
                        {
                            "mData": "div",
                            "sType": "numeric",
                            "sWidth": "2%",
                            "sClass": "numeric",
                            "sName": "div"
                        }
                    ],
                    "sPaginationType": "full_numbers",
                    //sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
                    sDom: '<"block-controls"<"export-buttons"><"controls-buttons"p><"my-buttons">>rti<"block-footer clearfix"lf>',

                    /* Callback to apply template setup*/
                    fnDrawCallback: function(){
                        $(this).slideDown(200);
                        $(".with-tip").tip();
                        var html = '<div style="float: right; margin-top:-5px">' +
                                            '<button action="history" class="with-tip" type="button">' + trans('bt_history') + '</button> ' +
                                            '<button action="today" class="with-tip red" type="button">' + trans('bt_today')  + '</button> ' +
                                        '</div>' +
                                        '<div style="clear: left;"></div>';
                        $(".my-buttons").html(html);
                        oTable.fnSetFilteringPressEnter();
                    },
                    fnInitComplete: function(){
                        $(this).slideDown(200);
                    }
                });
            },
            corporate: function(){
                $(document).ready(function(){
                    $("#event-dialog").dialog({
                        modal: true,
                        autoOpen: false,
                        closeOnEscape: true,
                        width: 500
                    });
                    var value_type = new Array();
                    $("input[type=checkbox].selAllChksInGroup").on("click.chkAll", function( event ){
                        $("#filter").removeAttr("ids");
                        $(this).parents('.block-content:eq(0)').find(':checkbox').prop('checked', this.checked);
                        value_type = new Array();
                        $.each($("input[name='stats-display[]']:checked"), function() {
                            value_type.push($(this).val());
                        });
                        $("#filter").attr("ids",value_type);
                    });
                    $("input[name='stats-display[]']").click(function() {
                        $("#filter").removeAttr("ids");
                        value_type = new Array();
                        $.each($("input[name='stats-display[]']:checked"), function() {
                            value_type.push($(this).val());
                        });
                        $("#filter").attr("ids",value_type);
                    });
                    $("#filter").click(function(){
                        var $data_value = $("#filter").attr("ids");
                        if($data_value.length != 0){
                            eventsListView.table_filter($data_value);
                        }else{
                             $(".grid_8").hide();
                        }
                    });
                });
            },

            table_filter:function($data_value){
                $(".grid_8").show();
                if(check_file_exists($base_url + 'assets/language/datatables/' + $lang + '.txt')){
                    $file = $base_url + 'assets/language/datatables/' + $lang + '.txt';
                }else{
                    $file = $base_url + 'assets/language/datatables/eng.txt';
                }
                if(typeof oTable != 'undefined'){
                    $("#vndb_events_day").dataTable().fnDestroy();
                }
                
                oTable = $('.table-events-list').dataTable({
                    "oLanguage":{
                        "sUrl": $file
                    },
                    "sScrollX": "100%",
                    // "sScrollXInner": "110%",
                    "bScrollCollapse": true,
                    "iDisplayLength": 10,
                    "iDisplayStart": 0,
                    "bProcessing": true,
                    "bRetrieve": true,
                    "aaSorting": [],
                    "bAutoWidth": true,
                    "bServerSide": true,
                    "sAjaxSource": $admin_url+"events/get_data_by_filter",
                    "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                        aoData.push(
                            {
                                name: 'value_type',
                                value: $data_value
                            }
                        );
                        $.ajax( {
                            "dataType": 'json',
                            "type": "POST",
                            "url": sSource,
                            "data": aoData,
                            success: function(rs){
                                fnCallback(rs);
                            }
                        });
                    },
                    "aoColumns": [
                        {
                            "mData": "ticker",
                            "sType": "string",
                            "sWidth": "7%",
                            "sClass": "string",
                            "sName": "ticker"
                        },
                        {
                            "mData": "market",
                            "sType": "string",
                            "sWidth": "7%",
                            "sClass": "string",
                            "sName": "market"
                        },
                        {
                            "mData": "date_ann",
                            "sType": "string",
                            "sWidth": "12%",
                            "sClass": "string",
                            "sName": "date_ann"
                        },
                        {
                            "mData": "event_type",
                            "sType": "string",
                            "sWidth": "12%",
                            "sClass": "string",
                            "sName": "event_type"
                        },
                        {
                            "mData": "evname",
                            "sType": "numeric",
                            "sWidth": "27%",
                            "sClass": "string",
                            "sName": "evname"
                        },
                        {
                            "mData": "content",
                            "sType": "string",
                            "sWidth": "70%",
                            "sClass": "string",
                            "sName": "content"
                        }
                    ],
                    "sPaginationType": "full_numbers",
                    //sDom: '<"block-controls"<"controls-buttons"p>>rti<"block-footer clearfix"lf>',
                    sDom: '<"block-controls"<"export-buttons"><"controls-buttons"p><"my-buttons">>rti<"block-footer clearfix"lf>',

                    /* Callback to apply template setup*/
                    fnDrawCallback: function(){
                        $(this).slideDown(200);

                        $(".with-tip").tip();

                        var html = '<div style="float: right; margin-top:-5px">' +
                                    '</div>' +
                                    '<div style="clear: left;"></div>';
                        $(".my-buttons").html(html);
                        html = '<ul class="controls-buttons"><li>' +
                                '<a style="cursor: pointer" action="export" class="with-tip" type="button">' + trans('bt_export') + '</a>'+
                                '</li></ul>' +
                                '<div style="clear: left;"></div>';
                        $(".export-buttons").html(html);
                        $("#custom-date").datepicker({
                            dateFormat: 'yy-mm-dd',
                            onSelect: function(selected){
                                $(location).attr('href', $admin_url + 'events/index/' + selected);
                            }
                        });
                        oTable.fnSetFilteringPressEnter();
                    },
                    fnInitComplete: function(){
                        $(this).slideDown(200);
                    }
                });
            },

            viewMore: function(event){
                var $this = $(event.currentTarget);
                var text = $($this).attr("content");//.val();
                var header = $($this).attr('header');
                $("#event-dialog").html(text).dialog("option", "title", header);
                $("#event-dialog").html(text).dialog("open");

            },

            goTo: function(event){
                var $this = $(event.currentTarget);
                var action = $($this).attr("action");
                switch(action){
                    case 'add':
                        $(location).attr("href", $admin_url + "events/" + action);
                    break;
                    case 'export':
                        var where = new Array();
                        var order = new Array();
                        var url = location.href;
                        var urls = url.split('/');
                        url = urls[urls.length - 1];
                        var tmp_where = {
                            expr1: 'date_ex',
                            op: '',
                            expr2: '_date_now'
                        };
                        var tmp_order = {
                            value: 'date_ex',
                            type: '',
                        }
                        switch(url){
                            case 'history': tmp_where.op = '<'; tmp_order.type = 'DESC'; break;
                            case 'future': tmp_where.op = '>'; tmp_order.type = 'ASC'; break;
                            case 'today': tmp_where.op = ''; break;
                            case 'index': 
                            case 'events': tmp_where.op = '>='; tmp_order.type = 'DESC'; break;
                            default: tmp_where.expr2 = url; break; 
                        }
                        where.push(tmp_where);
                        var oSettings = $('#vndb_events_final').dataTable().fnSettings();
                        var filter = oSettings.oPreviousSearch.sSearch;
                        
                        if(filter != ''){
                            var headers = new Array();
                            $.each(oSettings.aoColumns, function(key, item){
                                if(item.sName != ''){
                                    headers.push(item.sName);
                                }
                            });
                            tmp_where = {
                                sSearch: filter,
                                headers: headers
                            }
                            where.push(tmp_where);
                        }
                        order.push(tmp_order);
                        exportTable2('vndb_events_final', where, order, ['notice', 'id', 'date_cnf', 'confirm']);
                    break;
                    default:
                        var url = location.href;
                        var array_url = url.split('/');
                        if(array_url[6] == 'prepare'){
                            $(location).attr("href", $admin_url + "events/prepare/" + action);
                        }else if(array_url[6] == 'view'){
                            $(location).attr("href", $admin_url + "events/view/" + action);
                        }else{
                            $(location).attr("href", $admin_url + "events/index/" + action);
                        }
                        
                    break;
                }
            },

            getCheckDate: function(event){
                var $this = $(event.currentTarget);
                var check = $($this).val();
                if($($this).attr('checked')){
                    $("#date_cnf").val("now");
                }else{
                    $("#date_cnf").val("");
                }
                // if($($this).)
            },

            showCustomDate: function(event){
                var $this = $(event.currentTarget);
                var $keycode = (event.keycode) ? event.keycode : event.which;
                var date = $($this).val();
                if($keycode == 13){
                    $(location).attr('href', $admin_url + 'events/index/' + date);
                }
            },

            import: function(event){
                var $this = $(event.currentTarget);
                var text = encodeURIComponent($("#events-text").val());
                
                var market = $(".market:checked").val();
                $.ajax({
                    url: $admin_url + 'events/import',
                    type: 'post',
                    data: 'text=' + text + '&market=' + market,
                    async: false,
                    success: function(rs){
                        rs = JSON.parse(rs);
                        var data = rs.data;
                        $.each(data, function(k, value){
                            $("#" + k).val(value);
                        });
                    }
                })
            },

            edit: function(){
                $(".datepicker1").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            },

            back: function(){
                history.back(1);
            },

            cancel: function(){
                var url = location.href;
                var array_url = url.split('/');
                if(array_url[6] == 'add'){
                    window.location.href = $admin_url+'events';
                }
                if(array_url[6] == 'edit'){
                    window.location.href = $admin_url+'events/prepare';
                }
            },

            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return eventsListView = new eventsListView;
    });

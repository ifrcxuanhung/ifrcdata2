// Filename: views/download_histoday/list
define([
    'jquery',
    'underscore',
    'backbone',
    'text!templates/caculation/report.html'
], function($, _, Backbone, reportTemplate) {
    var downloadView = Backbone.View.extend({
        list: new Array(),
        el: $(".main-container"),
        initialize: function() {
            var html = 'Downloading<br><img width="100" src="' + $base_url + 'assets/templates/backend/images/preloader.gif">';
            $("#lean_overlay center").html(html);
        },
        events: {
            "click .submit": "excute",
            "change #cate-filter": "listFilter",
            "change #detail-filter": "listData",
            "click a.btn-excute": "preDownload",
            "click #excute": "doList",
            "click input.chk": "chkCode"
        },
        chkCode: function(event) {
            var $this = $(event.currentTarget);
            var code = $($this).attr('code');
            if ($($this).prop('checked')) {
                downloadView.list.push(code);
            } else {
                $.each(downloadView.list, function(k, item) {
                    if (item == code) {
                        downloadView.list.splice(k, 1);
                    }
                });
            }
            console.log(downloadView.list);
        },
        doList: function() {
            var $html = '<ul class="blocks-list">' +
                    '<li>' +
                    '<a href="#" class="float-left"><img src="' + $base_url + 'assets/templates/backend/images/icons/fugue/status.png" width="16" height="16">From</a>' +
                    '<div class="columns">' +
                    '<p class="colx2-right">' +
                    '<input type="text" name="startdate" id="startdate" value="" style="width: 60%"> <img width="16" height="16" src="' + $base_url + 'assets/templates/backend/images/icons/fugue/calendar-month.png">' +
                    '</p>' +
                    '</div>' +
                    '</li>' +
                    '<li>' +
                    '<a href="#" class="float-left"><img src="' + $base_url + 'assets/templates/backend/images/icons/fugue/status.png" width="16" height="16">To</a>' +
                    '<div class="columns">' +
                    '<p class="colx2-right">' +
                    '<input type="text" name="enddate" id="enddate" value="" style="width: 60%"> <img width="16" height="16" src="' + $base_url + 'assets/templates/backend/images/icons/fugue/calendar-month.png">' +
                    '</p>' +
                    '</div>' +
                    '</li>' +
                    '</ul>';
            downloadView.openModal('Choose time', $html, 400);
            downloadView.openModal("Choose time", $html, 400);
            $("#modal").click(function() {
                $(this).remove();
            });
            $(".modal-window").click(function(e) {
                e.stopPropagation();
            });
            $("#modal").livequery(function() {
                $("#startdate").datepicker({
                    maxDate: 0,
                    dateFormat: 'yy-mm-dd',
                    onSelect: function(selected) {
                        $("#enddate").datepicker("option", "minDate", selected);
                        $("#enddate").val($("#startdate").val());
                    }
                });
                $("#enddate").datepicker({
                    maxDate: 0,
                    dateFormat: 'yy-mm-dd',
                    onSelect: function(selected) {
                        $("#startdate").datepicker("option", "maxDate", selected);
                    }
                });
                if ($("#modal button").data('events').click.length == 1) {
                    $("#modal button").click(function() {
                        $("#lean_overlay").show(100, function() {
                            list = downloadView.list;
                            $start = $("#startdate").val();
                            $end = $("#enddate").val();
                            $.each(list, function(k, item) {
                                downloadView.doAjax(item, $start, $end, '');
                            });
                            $("#lean_overlay").hide();
                            alert("Finish");
                        });
                    });
                }
            });

        },
        openModal: function($title, $content, $width) {
            $.modal({
                content: $content,
                title: $title,
                maxWidth: 2500,
                width: $width,
                buttons: {
                    'Go': function(win) {
                    }
                }
            });
        },
        doDownload: function() {
            //
        },
        preDownload: function(event) {
            $("#lean_overlay").show(100, function() {
                var $this = $(event.currentTarget);
                var code_dwl = $($this).attr('code');
                var $time = $($this).attr('date');
                var $input = $($this).attr('input');
                var $start = '';
                var $end = '';
                if ($time != 'TODAY' && $input != 'XLS') {
                    $("#lean_overlay").hide();
                    var $html = '<ul class="blocks-list">' +
                            '<li>' +
                            '<a href="#" class="float-left"><img src="' + $base_url + 'assets/templates/backend/images/icons/fugue/status.png" width="16" height="16">From</a>' +
                            '<div class="columns">' +
                            '<p class="colx2-right">' +
                            '<input type="text" name="startdate" id="startdate" value="" style="width: 60%"> <img width="16" height="16" src="' + $base_url + 'assets/templates/backend/images/icons/fugue/calendar-month.png">' +
                            '</p>' +
                            '</div>' +
                            '</li>' +
                            '<li>' +
                            '<a href="#" class="float-left"><img src="' + $base_url + 'assets/templates/backend/images/icons/fugue/status.png" width="16" height="16">To</a>' +
                            '<div class="columns">' +
                            '<p class="colx2-right">' +
                            '<input type="text" name="enddate" id="enddate" value="" style="width: 60%"> <img width="16" height="16" src="' + $base_url + 'assets/templates/backend/images/icons/fugue/calendar-month.png">' +
                            '</p>' +
                            '</div>' +
                            '</li>' +
                            '</ul>';
                    downloadView.openModal("Choose time", $html, 400);
                    $("#modal").click(function() {
                        $(this).remove();
                    });
                    $(".modal-window").click(function(e) {
                        e.stopPropagation();
                    });
                    $("#modal").livequery(function() {
                        $("#startdate").datepicker({
                            maxDate: 0,
                            dateFormat: 'yy-mm-dd',
                            onSelect: function(selected) {
                                $("#enddate").datepicker("option", "minDate", selected);
                                $("#enddate").val($("#startdate").val());
                            }
                        });
                        $("#enddate").datepicker({
                            maxDate: 0,
                            dateFormat: 'yy-mm-dd',
                            onSelect: function(selected) {
                                $("#startdate").datepicker("option", "maxDate", selected);
                            }
                        });
                        if ($("#modal button").data('events').click.length == 1) {
                            $("#modal button").click(function() {
                                $("#lean_overlay").show(100, function() {
                                    $start = $("#startdate").val();
                                    $end = $("#enddate").val();
                                    downloadView.doAjax(code_dwl, $start, $end, '');
                                });
                            });
                        }
                    });
                } else {
                    downloadView.doAjax(code_dwl, $start, $end, '');
                }
                alert("Finish");
                location.href = $admin_url;
            });
        },
        doAjax: function(code, $start, $end, $dir) {
            var check;
            $.ajax({
                url: $admin_url + 'download/getInfo',
                type: 'post',
                data: 'code=' + code,
                async: false,
                success: function(rs) {
                    if (rs == 0) {
                        alert('No suitable data in database');
                    } else {
                        options = JSON.parse(rs);
                    }
                }
            });
            $(options).each(function(i) {
                if (options[i].multipages == 0) {
                    options[i].multipages = 1;
                }
                for (var j = 1; j <= options[i].multipages; j++) {
                    // for(var j = 0; j <= 0; j++){
                    $.ajax({
                        url: $admin_url + 'download/download_links',
                        type: 'post',
                        data: {
                            options: options[i],
                            page: j,
                            start: $start,
                            end: $end,
                            ticker: (typeof ticker == 'undefined') ? '' : ticker,
                            dir: $dir
                        },
                        async: false,
                        success: function(rs) {
                            if (rs != '') {
                                rs = JSON.parse(rs);
                                if (rs.file_exist == 1) {
                                    check = rs.file_exist;
                                }
                                if (typeof rs.ticker == 'object') {
                                    ticker = rs.ticker;
                                }
                            }

                        }
                    });
                    if (check == 1) {
                        return;
                    }
                }
            });
        },
        listData: function(event) {
            var $this = $(event.currentTarget);
            var value = $($this).val();
            var cate = $("#cate-filter").val();
            // value = cate + "|" + value;
            // this.links(value);
            index = $("th").index($("#" + cate));
            oTable.fnFilter(value, index);
        },
        listFilter: function(event) {
            var $this = $(event.currentTarget);
            var value = $($this).val();
            $.ajax({
                url: $admin_url + 'download/listByColumn',
                type: 'post',
                data: 'column=' + value,
                success: function(rs) {
                    if (rs == 0) {
                        window.location.reload(false);
                        return;
                    }
                    rs = JSON.parse(rs);
                    $("#detail-filter").html("");
                    // $("#detail-filter").html("<option value='0'>Choose to filter...</option>");
                    // $("#detail-filter").append("<option value='all'>&nbsp;&nbsp;&nbsp;>>ALL</option>");
                    $.each(rs, function(i, item) {
                        var options = "<option value='" + item + "'>" + item + "</option>";
                        if (item == "ALL") {
                            options = "<option value='" + item + "' selected>" + item + "</option>"
                            $("#detail-filter").prepend(options);
                        } else {
                            $("#detail-filter").append(options);
                        }
                    });
                    var detail = $("#detail-filter").val();
                    index = $("th").index($("#" + value));
                    oTable.fnFilter(detail, index);
                }
            });
        },
        excute: function() {
            $("#lean_overlay").show(100, function() {
                var options;
                request = {
                    'code_dwl': $("#code_dwl").val()
                };
                if (request.code_dwl == 0) {
                    request.code_info = $("#code_info").val();
                    request.market = $("#market").val();
                    request.url = $("#url").val();
                    request.input = $("#input").val();
                    request.time = $("#time").val();
                }
                // request = JSON.stringify(request);
                $.ajax({
                    url: $admin_url + 'download/getOptions',
                    type: 'post',
                    data: request,
                    async: false,
                    success: function(rs) {
                        if (rs == 0) {
                            alert('No suitable data in database');
                        } else {
                            options = JSON.parse(rs);
                        }
                    }
                });
                $(options).each(function(i) {
                    if (options[i].multipages == 0) {
                        options[i].multipages = 1;
                    }
                    for (var j = 0; j <= options[i].multipages; j++) {
                        $.ajax({
                            url: $admin_url + 'download/getData',
                            type: 'post',
                            data: {
                                options: options[i],
                                page: j
                            },
                            async: false,
                            success: function(rs) {

                            }
                        });
                    }
                });
            });
        },
        histoday: function() {
        },
        links: function() {
            $str_url = $admin_url + 'download/links';
            if (check_file_exists($base_url + 'assets/language/datatables/' + $lang + '.txt')) {
                $file = $base_url + 'assets/language/datatables/' + $lang + '.txt';
            } else {
                $file = $base_url + 'assets/language/datatables/eng.txt';
            }
            oTable = $('.table-dividends-list').dataTable({
                "oLanguage": {
                    "sUrl": $file
                },
                //"bRetrieve": true,
                "iDisplayLength": 10,
                "iDisplayStart": 0,
                "bProcessing": true,
                "aaSorting": [],
                "sAjaxSource": $str_url,
                "aoColumnDefs ": [
                    {
                        "mData": "check"
                    },
                    {
                        "mData": "source",
                        "sType": "string"
                    },
                    {
                        "mData": "code_dwl",
                        "sType": "string"
                    },
                    {
                        "mData": "market",
                        "sType": "string"
                    },
                    {
                        "mData": "language",
                        "sType": "string"
                    },
                    {
                        "mData": "information",
                        "sType": "string"
                    },
                    {
                        "mData": "url",
                        "sType": "string"
                    },
                    {
                        "mData": "time",
                        "sType": "string"
                    },
                    {
                        "mData": "input",
                        "sType": "string"
                    },
                    {
                        "mData": "output",
                        "sType": "string"
                    },
                    {
                        "mData": "action"
                    },
                    {
                        "mData": "done",
                        "sType": "number"
                    }
                ],
                "sPaginationType": "full_numbers",
                sDom: '<"block-controls"<"controls-buttons"p>f>rti<"block-footer clearfix"l>',
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

            $("a.copy").livequery(function() {
                if ($(this).next("div").length == 0) {
                    $(this).zclip({
                        path: $base_url + 'assets/templates/backend/js/ZeroClipboard10.swf',
                        copy: function() {
                            return html_entity_decode($(this).html());
                        },
                        beforeCopy: function() {
                        },
                        afterCopy: function() {
                        }
                    });
                }
            });
        },
        action: function() {
            if (window.confirm("Do you want to continue?")) {
                downloadView.download('download/action');
            }

        },
        download_list: function(list, $start, $end, $dir) {
            $.each(list, function(k, item) {
                downloadView.doAjax(item, $start, $end, $dir);
            });
        },
        ownership: function() {
            $dir = '\\\\LOCAL\\IFRCVN\\VNDB\\METASTOCK\\OWNERSHIP\\SOURCES\\';
            list = ['STBALLTW', 'STPOWNERTW', 'CAFOWNERTW', 'CPHALLTW'];
            // list = ['STPOWNERTW','CAFOWNERTW'];
            var remains = new Array();
            // downloadView.list = ['CPHALLTW'];
            $("#lean_overlay").show(100, function() {
                downloadView.download_list(list, '', '', $dir);
                $.each(list, function(k, item) {
                    var path = $dir + item.substr(0, 3) + '\\';
                    tickers = downloadView.checkTicker(path, item, '', 1);
                    remains.push({
                        code: item,
                        ticker: (typeof tickers == 'object') ? tickers.join(', ') : 'Completed'
                    });
                });
                $html = '<ul class="blocks-list">';
                $.each(remains, function(k, item) {
                    $html += '<li>' +
                            '<a class="float-left" href="#"><img width="16" height="16" src="' + $template_url + 'images/icons/fugue/status.png"> ' + item.code + '</a>' +
                            '<ul class="tags float-right">' +
                            '<li>' + item.ticker + '</li>' +
                            '</ul>' +
                            '</li>';
                });
                $html += '</ul>';
                $("#lean_overlay").hide();
                openModal('FINISH', $html, 400);
            });
        },
        dividend: function() {
            $dir = '\\\\LOCAL\\IFRCVN\\VNDB\\METASTOCK\\DIVIDEND\\FINAL\\';
            list = ['FPTDIVHW', 'CPHDIVCASTW', 'STPDIVALLTW', 'STBDIVALLTW', 'VSTDIVPATW'];
            // list = ['VSTDIVPATW'];
            // downloadView.doList();                
            var remains = new Array();
            $("#lean_overlay").show(100, function() {
                downloadView.download_list(list, '', '', $dir);
                $.each(list, function(k, item) {
                    var path = $dir + item.substr(0, 3) + '\\';
                    tickers = downloadView.checkTicker(path, item, '', 1);
                    remains.push({
                        code: item,
                        ticker: (typeof tickers == 'object') ? tickers.join(', ') : 'Completed'
                    });
                });
                $html = '<ul class="blocks-list">';
                $.each(remains, function(k, item) {
                    $html += '<li>' +
                            '<a class="float-left" href="#"><img width="16" height="16" src="' + $template_url + 'images/icons/fugue/status.png"> ' + item.code + '</a>' +
                            '<ul class="tags float-right">' +
                            '<li>' + item.ticker + '</li>' +
                            '</ul>' +
                            '</li>';
                });
                $html += '</ul>';
                $("#lean_overlay").hide();
                openModal('FINISH', $html, 400);
            });
        },
        dividend_daily: function() {
            $dir = '\\\\LOCAL\\IFRCVN\\VNDB\\METASTOCK\\DIVIDEND\\DAILY\\';
            list = ['FPTDIVHW1', 'CPHDIVCASTW1', 'STBDIVALLTW1', 'VSTDIVPATW1'];
            // list = ['STBDIVALLTW1'];
            // downloadView.doList();                
            var remains = new Array();
            $("#lean_overlay").show(100, function() {
                downloadView.download_list(list, '', '', $dir);
                $.each(list, function(k, item) {
                    var path = $dir + item.substr(0, 3) + '\\';
                    tickers = downloadView.checkTicker(path, item, '', 1);
                    remains.push({
                        code: item,
                        ticker: (typeof tickers == 'object') ? tickers.join(', ') : 'Completed'
                    });
                });
                $html = '<ul class="blocks-list">';
                $.each(remains, function(k, item) {
                    $html += '<li>' +
                            '<a class="float-left" href="#"><img width="16" height="16" src="' + $template_url + 'images/icons/fugue/status.png"> ' + item.code + '</a>' +
                            '<ul class="tags float-right">' +
                            '<li>' + item.ticker + '</li>' +
                            '</ul>' +
                            '</li>';
                });
                $html += '</ul>';
                $("#lean_overlay").hide();
                openModal('FINISH', $html, 400);
            });
        },
        event_hsx: function() {
            $("#lean_overlay").show();
            var start = new Date().getTime() / 1000;
            var task = [];
            $.ajax({
                url: $admin_url + 'event/hsx',
                type: 'POST',
                cache: false,
                success: function() {
                    var end = new Date().getTime() / 1000;
                    task.push({
                        "task": trans("bt_finish", 1),
                        "time": (end - start).toFixed(2)
                    });
                    $("#lean_overlay").hide();
                    $.modal({
                        content: "<div class='download-event-hsx-report'></div>",
                        title: trans("mn_download_event_hsx", 1),
                        maxWidth: 2500,
                        width: 400,
                        buttons: {
                            'Close': function(win) {
                                win.closeModal();
                            }
                        }
                    });
                    var datatemplate = {};
                    datatemplate.report = task;
                    var compiledTemplate = _.template(reportTemplate, datatemplate);
                    $('.download-event-hsx-report').html(compiledTemplate).fadeIn();

                },
                error: function() {
                    var end = new Date().getTime() / 1000;
                    task.push({
                        "task": trans("error", 1),
                        "time": (end - start).toFixed(2)
                    });
                    $("#lean_overlay").hide();
                }
            });
        },
        event_hnx: function() {
            $("#lean_overlay").show();
            var start = new Date().getTime() / 1000;
            var task = [];
            $.ajax({
                url: $admin_url + 'event/hnx',
                type: 'POST',
                cache: false,
                success: function() {
                    var end = new Date().getTime() / 1000;
                    task.push({
                        "task": trans("bt_finish", 1),
                        "time": (end - start).toFixed(2)
                    });
                    $("#lean_overlay").hide();
                    $.modal({
                        content: "<div class='download-event-hnx-report'></div>",
                        title: trans("mn_download_event_hnx", 1),
                        maxWidth: 2500,
                        width: 400,
                        buttons: {
                            'Close': function(win) {
                                win.closeModal();
                            }
                        }
                    });
                    var datatemplate = {};
                    datatemplate.report = task;
                    var compiledTemplate = _.template(reportTemplate, datatemplate);
                    $('.download-event-hnx-report').html(compiledTemplate).fadeIn();

                },
                error: function() {
                    var end = new Date().getTime() / 1000;
                    task.push({
                        "task": trans("error", 1),
                        "time": (end - start).toFixed(2)
                    });
                    $("#lean_overlay").hide();
                }
            });
        },
        news_hsx: function() {
            $("#lean_overlay").show();
            var start = new Date().getTime() / 1000;
            var task = [];
            $.ajax({
                url: $admin_url + 'news/hsx',
                type: 'POST',
                cache: false,
                success: function() {
                    var end = new Date().getTime() / 1000;
                    task.push({
                        "task": trans("bt_finish", 1),
                        "time": (end - start).toFixed(2)
                    });
                    $("#lean_overlay").hide();
                    $.modal({
                        content: "<div class='download-news-hsx-report'></div>",
                        title: trans("mn_download_news_hsx", 1),
                        maxWidth: 2500,
                        width: 400,
                        buttons: {
                            'Close': function(win) {
                                win.closeModal();
                            }
                        }
                    });
                    var datatemplate = {};
                    datatemplate.report = task;
                    var compiledTemplate = _.template(reportTemplate, datatemplate);
                    $('.download-news-hsx-report').html(compiledTemplate).fadeIn();

                },
                error: function() {
                    var end = new Date().getTime() / 1000;
                    task.push({
                        "task": trans("error", 1),
                        "time": (end - start).toFixed(2)
                    });
                    $("#lean_overlay").hide();
                }
            });
        },
        cpaction_update: function() {
            $("#lean_overlay").show();
            $dir = '\\\\LOCAL\\IFRCVN\\VNDB\\METASTOCK\\DIVIDEND\\DAILY\\';
            var start = new Date().getTime() / 1000;
            $.ajax({
                url: $admin_url + 'download/cpaction_update',
                type: 'POST',
                data: 'dir=' + $dir,
                async: false,
                success: function(rs) {
                    $("#lean_overlay").hide();
                    var end = new Date().getTime() / 1000;
                    var time = [{
                            "task": rs,
                            "time": (end - start).toFixed(2)
                        }];
                    $.modal({
                        content: "<div class='cpaction-update-report'></div>",
                        title: trans("mn_CPAction_Update", 1),
                        maxWidth: 2500,
                        width: 400,
                        buttons: {
                            'Close': function(win) {
                                win.closeModal();
                            }
                        }
                    });
                    var datatemplate = {};
                    datatemplate.report = time;
                    var compiledTemplate = _.template(reportTemplate, datatemplate);
                    $('.cpaction-update-report').html(compiledTemplate).fadeIn();
                },
                error: function() {
                    $("#lean_overlay").hide();
                }
            });
        },
        checkTicker: function(path, code, market, pos) {
            var remains;
            $.ajax({
                url: $admin_url + 'download/checkTicker',
                type: 'post',
                data: 'path=' + path + '&code=' + code + '&market=' + market + '&pos=' + pos,
                async: false,
                success: function(rs) {
                    remains = JSON.parse(rs);
                    // console.log(remains);
                }
            });
            return remains
        },
        test: function() {
            alert(123);
        },
        import_equity: function() {
            $.modal({
                title: trans("mn_confirmation", 1),
                content: trans("do_you_want_to_import_idx_equity", 1),
                width: 400,
                buttons: {
                    "Yes": function(win) {
                        win.remove();
                        downloadView.do_import_equity();
                    },
                    "Close": function(win) {
                        win.closeModal();
                    }
                }
            })
        },
        do_import_equity: function() {
            $('#lean_overlay').show();
            var start = "";
            var end = "";
            var task = "";
            var finish = trans("bt_finish", 1);
            var time = [];
            start = new Date().getTime() / 1000;
            $.ajax({
                type: "POST",
                async: false,
                url: $admin_url + "equity/import_idx_equity_day",
                success: function(rsday) {
                    rsday = JSON.parse(rsday);
                    end = new Date().getTime() / 1000;
                    if (typeof rsday.err != "object") {
                        task = trans("bt_idx_equity_day", 1) + " " + finish;
                    } else {
                        task = trans("bt_idx_equity_day", 1) + " " + rsday.err;
                    }
                    time.push({
                        "task": task,
                        "time": (end - start).toFixed(2)
                    });
                },
                error: function() {
                    return;
                }
            });
            start = new Date().getTime() / 1000;
            $.ajax({
                type: "POST",
                async: false,
                url: $admin_url + "equity/import_idx_equity_month",
                success: function(rsmonth) {
                    rsmonth = JSON.parse(rsmonth);
                    end = new Date().getTime() / 1000;
                    if (typeof rsmonth.err != "object") {
                        task = trans("bt_idx_equity_month", 1) + " " + finish;
                    } else {
                        task = trans("bt_idx_equity_month", 1) + " " + rsmonth.err;
                    }
                    time.push({
                        "task": task,
                        "time": (end - start).toFixed(2)
                    });
                },
                error: function() {
                    return;
                }
            });
            start = new Date().getTime() / 1000;
            $.ajax({
                type: "POST",
                async: false,
                url: $admin_url + "equity/import_idx_equity_year",
                success: function(rsyear) {
                    rsyear = JSON.parse(rsyear);
                    end = new Date().getTime() / 1000;
                    if (typeof rsyear.err != "object") {
                        task = trans("bt_idx_equity_year", 1) + " " + finish;
                    } else {
                        task = trans("bt_idx_equity_year", 1) + " " + rsyear.err;
                    }
                    time.push({
                        "task": task,
                        "time": (end - start).toFixed(2)
                    });
                },
                error: function() {
                    return;
                }
            });
            $('#lean_overlay').hide();
            $.modal({
                title: trans("mn_import_equity"),
                content: "<div class='import-equity-report'></div>",
                width: 400,
                buttons: {
                    "Close": function(win) {
                        win.closeModal();
                    }
                }
            });
            var datatemplate = {};
            datatemplate.report = time;
            var compiledTemplate = _.template(reportTemplate, datatemplate);
            $('.import-equity-report').html(compiledTemplate).fadeIn();
        },
        download: function(action) {
            $("#lean_overlay").show(100, function() {
                $.ajax({
                    url: $admin_url + action,
                    async: false,
                    success: function(rs) {
                        rs = JSON.parse(rs);
                        datatemplate = new Object;
                        datatemplate.report = rs.report;
                        var compiledTemplate = _.template(reportTemplate, datatemplate);
                        $("#lean_overlay").hide();
                        openModal('Report', compiledTemplate, 400);
                    }
                });
            });
        },
        bloomberg: function() {
            downloadView.download('bloomberg');
        },
        prices: function() {
            downloadView.download('prices');
        },
        downDataHNX: function(event) {
            var $title = trans("confirmation", 1);
            var $content = trans("are_you_sure", 1);
            $.modal({
                content: $content,
                title: $title,
                maxWidth: 2500,
                width: 400,
                buttons: {
                    'Yes': function() {
                        $("#modal").remove();

                        $.ajax({
                            type: "POST",
                            url: $admin_url + "download/downDataHNX",
                            datatype: "json",
                            success: function(response) {
                                var $listTickers = JSON.parse(response);
                                $.each($listTickers, function(i, item) {

                                    $.ajax({
                                        type: "POST",
                                        async: false,
                                        cache: false,
                                        url: $admin_url + "download/getDataHNX",
                                        data: "ticker=" + item.ticker,
                                        success: function() {
                                            console.log("done");
                                        },
                                        error: function(jqXHR, exception) {
                                            ajaxError(jqXHR, exception);
                                        }
                                    });

                                });
                            },
                            error: function(jqXHR, exception) {
                                ajaxError(jqXHR, exception);
                            }
                        });
                    },
                    'No': function() {
                        $("#modal").remove();
                    }
                }
            });
            $(".modal-window .block-content .block-footer").find("button:eq(1)").attr("class", "red");
        },
        render: function() {
            if (typeof this[$app.action] != 'undefined') {
                new this[$app.action];
            }
        }
    });
    return downloadView = new downloadView;
});

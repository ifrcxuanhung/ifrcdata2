// Filename: views/sysformat/list
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var sysformatListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click a.action-delete": "doDelete",
                "click a.action": "showTable",
                "click .active": "doActive",
                "mouseover div.frozen-bdiv": "doHighlight",
                "mouseout div.frozen-bdiv": "notHighlight"
            },
            doHighlight: function(event){
                var $this = $(event.currentTarget);
                $($this).children('tr').addClass('ui-state-highlight');
            },
            notHighlight: function(event){

            },
            doDelete:function(event){
                var $this=$(event.currentTarget);
                if (confirm('Are you sure?')) {
                    var id=$($this).attr('media_id');
                    $.ajax({
                        type: "POST",
                        url: $admin_url+"media/delete",
                        data: "id="+id,
                        success: function(msg){
                            if(msg>=1){
                                $($this).parents('tr').fadeOut('slow');
                            }else{
                                alert('delete media unsuccess');
                            }
                        }
                    });
                }
            },

            doActive: function(event){
                var $this = $(event.currentTarget);
                var status;
                var id=$(this).attr('id');
                if($($this).prop('checked')){
                    status = '1';
                }else{
                    status = '0';
                }
                $.ajax({
                    url: $admin_url + 'sysformat/active?status=' + status + '&id=' + id,
                    type: 'get',
                    async: false,
                    success: function(rs){
                        alert(rs);
                    }
                });
            },

            showTable: function(event){
                var $this = $(event.currentTarget);
                var url = $($this).attr('href');
                var title = $($this).attr('table');
                //var content = $.get(url);
              var content = '<div class="modal-table-view"><table id="modal-table-view-list" align="center"></table><div id="pager"></div></div>';
                //alert(content);
                var $widthModal=$('body').width() - 100;
                openModal(title, content,$widthModal);
                if($('#modal-table-view-list').length>0){
                    sysformatListView.index('#modal-table-view-list',title,($widthModal-10));
                }
                return false;
            },

            index: function($idRender,$table,$width){
                var homeView;
                if($width === undefined){
                    $width = $(window).width() * 92 / 100;
                }
                require(['views/home/list'], function(obj){
                    homeView = obj;
                });
                if($idRender==''|| $idRender==undefined)
                    $idRender='#list';
                if($table=='' || $table==undefined)
                    $table = 'sys_format';
                $(document).ready(function()
                {
                   var $string = location.search;
                    if($string != ''){
                      var $str_arr = $string.split('=');
                        $table = $str_arr[1];
                    }
                    var lastsel;
                    var config;
                    var getTable = function(current){
                        if(current === undefined){
                            current = 1;
                        }
                        $.ajax({
                            url: $base_url + 'backend/sysformat/getConfig?table=' + $table,
                            type: 'post',
                            async: false,
                            success: function(rs){
                                if(rs == 1){
                                    $('.modal-table-view').html('<div class="error message">Table does not exist in database</div>');
                                    return false;
                                }
                                if(rs != 0){
                                    $page = "#pager";
                                    $(".modal-table-view").html("<table id='" + $($idRender).attr('id') + "'></table><div id='" + $($page).attr('id') + "'></div>");
                                    config = JSON.parse(rs);
                                    //$.jgrid.no_legacy_api = true;
                                    //$.jgrid.useJSON = true;

                                    /*$(config.colModel).each(function(i){
                                        config.colModel[i].width = $width * config.colModel[i].width / 100;
                                    });*/

                                   var myGrid = $($idRender).jqGrid({
                                        url: $base_url + 'backend/sysformat/index?table=' + $table,
                                        datatype: 'json',
                                        mtype: 'GET',
                                        colNames: config.colNames,
                                        colModel : config.colModel,
                                        ondblClickRow: function(id){
                                            $($idRender).jqGrid('destroyFrozenColumns');
                                            if(id && id !== lastsel){
                                                jQuery($idRender).jqGrid('restoreRow', lastsel);
                                                jQuery($idRender).jqGrid('editRow', id, true);
                                                lastsel = id;
                                            }
                                            $($idRender).jqGrid('setFrozenColumns');
                                            $("#" + id).removeClass("ui-state-highlight");

                                        },
                                        beforeProcessing: function(data, status, xhr){
                                            if(!data){
                                                return false;
                                            }
                                        },
                                        resizeStop: function(newwidth,index){
                                            var columnNames = $($idRender)[0].p.colNames;
                                            //newwidth = newwidth / $width * 100;

                                            $.ajax({
                                                url: $base_url + 'backend/sysformat/updateWidth?table=' + $table + '&header=' + columnNames[index] + '&width=' + newwidth,
                                                type: 'get',
                                                async: false
                                            });
                                        },
                                        /*inlineSuccessSaveRow: function(){
                                            $($idRender).jqGrid('setFrozenColumns');
                                        },*/
                                        gridComplete: function(){
                                            if(typeof res !== 'undefined'){
                                                $(config.font_weight).each(function(i){
                                                    $(res.rows).each(function(j){
                                                        $id = res.rows[j].id;
                                                        $($idRender).setCell($id, config.font_weight[i],'',{'font-weight': 'bold'});
                                                        $($idRender + "_frozen #" + $id + " td[aria-describedby='" + $($idRender).attr("id") + "_" + config.font_weight[i] + "']").css('font-weight', 'bold');
                                                    });
                                                });
                                            }
                                        },
                                        loadComplete: function(response){                                            
                                            res = response;
                                            // homeView.fixPositionsOfFrozenDivs(this);
                                            $type_button = ["#add", "#edit", "#del"];
                                            $idGrid = $($idRender).attr("id");
                                            if(typeof $str_arr === 'undefined'){
                                                $($type_button).each(function(i){
                                                    $($type_button[i] + "_" + $idGrid).hide();
                                                });
                                            }

                                            $(config.font_weight).each(function(i){
                                                $(response.rows).each(function(j){
                                                    $id = response.rows[j].id;
                                                    $($idRender).setCell($id, config.font_weight[i],'',{'font-weight': 'bold'});
                                                    $($idRender + "_frozen #" + $id + " td[aria-describedby='" + $($idRender).attr("id") + "_" + config.font_weight[i] + "']").css('font-weight', 'bold');
                                                });
                                            });
                                        },
                                        editurl: $base_url + 'backend/sysformat/edit?table=' + $table,
                                        pager: jQuery('#pager'),
                                        rowNum:20,
                                        rowList:[10,20,30],
                                        sortname: 'id',
                                        sortorder: "desc",
                                        viewrecords: true,
                                        height: $(window).height() * 50 / 100,
                                        width: $width,
                                        caption: trans("sysformat_" + $table)
                                    }).jqGrid('navGrid','#pager');
                                    $($idRender).jqGrid('filterToolbar');
                                    $($idRender).jqGrid('setFrozenColumns');


                                //$jsgrdview.delGridRow("rowid", {delData});

                                $($idRender + "_frozen tr").live("dblclick", function(){
                                    $id = $(this).attr("id");
                                    myGrid[0].p.ondblClickRow($id);
                                });

                                }else{
                                    $('.modal-table-view').html('<div class="error message"><strong>Not insert into sys_format table</strong></div>');
                                    return false;
                                }
                            }
                        });
                    }
                    getTable();

                    $($idRender).jqGrid(
                        'navButtonAdd',
                        '#pager',
                        {
                            caption: trans('Export'),
                            title: trans('Export'),
                            onClickButton:function(){
                                $.ajax({
                                    url: $base_url + 'backend/sysformat/export?table=' + $table,
                                    type: 'get',
                                    async: false,
                                    success: function(rs){
                                        rs = JSON.parse(rs);
                                        window.location.href = $admin_url + 'sysformat/download_file?file=' + rs.file + '&path=' + rs.path;
                                    }
                                });
                            }
                        }
                    );
                });
            },
            test: function(){
                alert(234);
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return sysformatListView = new sysformatListView;
    });

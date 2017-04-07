// Filename: views/home/list
//require($base_url + "assets/apps/backend/views/sysformat/list.js")
define([
    'jquery',
    'underscore',
    'backbone'
    ], function($, _, Backbone){
        var homeListView = Backbone.View.extend({
            el: $(".main-container"),
            initialize: function(){
            },
            events: {
                "click a.action-delete": "doDelete",
                //"click a.order": "showColumnByOrder"
            },
            doDelete:function(event){
                var $this=$(event.currentTarget);
                if (confirm('Are you sure')) {
                    var id=$($this).attr("category_id");
                    $.ajax({
                        type: "POST",
                        url: $admin_url+"category/delete",
                        data: "id=" + id,
                        success: function(msg){
                            if(msg>=1){
                                $($this).parents('tr').fadeOut('slow');
                            }else{
                                alert('the child category still exists');
                            }
                        }
                    });
                }
            },
            fixPositionsOfFrozenDivs: function ($this) {
                var $rows;
                if (typeof $this.grid.fbDiv !== "undefined") {
                    $rows = $('>div>table.ui-jqgrid-btable>tbody>tr', $this.grid.bDiv);
                    $('>table.ui-jqgrid-btable>tbody>tr', $this.grid.fbDiv).each(function (i) {
                        var rowHight = $($rows[i]).height(), rowHightFrozen = $(this).height();
                        if ($($this).hasClass("jqgrow")) {
                            $($this).height(rowHight);
                            rowHightFrozen = $(this).height();
                            if (rowHight !== rowHightFrozen) {
                                $($this).height(rowHight + (rowHight - rowHightFrozen));
                            }
                        }
                    });
                    $($this.grid.fbDiv).height($this.grid.bDiv.clientHeight);
                    $($this.grid.fbDiv).css($($this.grid.bDiv).position());
                }
                if (typeof $this.grid.fhDiv !== "undefined") {
                    $rows = $('>div>table.ui-jqgrid-htable>thead>tr', $this.grid.hDiv);
                    $('>table.ui-jqgrid-htable>thead>tr', $this.grid.fhDiv).each(function (i) {
                        var rowHight = $($rows[i]).height(), rowHightFrozen = $($this).height();
                        $($this).height(rowHight);
                        rowHightFrozen = $($this).height();
                        if (rowHight !== rowHightFrozen) {
                            $($this).height(rowHight + (rowHight - rowHightFrozen));
                        }
                    });
                    $($this.grid.fhDiv).height($this.grid.hDiv.clientHeight);
                    $($this.grid.fhDiv).css($($this.grid.hDiv).position());
                }
            },
            showTable: function($idRender, $page, $url, $table, $width, $code){

                if($width === undefined || $width === ''){
                    var $width = $("article").width() * 98 / 100;
                }
                if($code === undefined){
                    $code = '';
                }
                
                var getTable = function(current){
                    if(current === undefined){
                        current = 1;
                    }
                    $.ajax({
                        url: $base_url + 'backend/home/getConfig?table=' + $table,
                        type: 'post',
                        async: false,
                        success: function(rs){
                            if(rs == 0){
                                $width = $width - 40;
                                $($idRender).html("<div class='message' style='width: " + $width + "px'><strong>Not insert table into sys_format</strong></div>");
                                return false;
                            }
                            rs = JSON.parse(rs);
                            $config = rs;
                            if($table === 'idx_specs'){
                                $grid = '#specs';
                            }
                            if($table === 'idx_composition'){
                                $grid = '#composition';
                            }
                            $($grid).html("<table id='" + $($idRender).attr('id') + "'></table><div id='" + $($page).attr('id') + "'></div>");
                            /*$($config.colModel).each(function(i){
                                $config.colModel[i].width = Math.round($width * $config.colModel[i].width / 100);
                                //$config.colModel[i];
                            });*/
                            /*$group = {};
                            $group['status'] = false;
                            $group['field'] = '';
                            if($table === 'idx_specs'){
                                $group['status'] = true;
                                $group['field'] = ['idx_mother'];
                            }*/
                            var myGrid = $($idRender).jqGrid({
                                url: $url + '?table=' + $table + '&code=' + $code,
                                datatype: 'json',
                                mtype: 'GET',
                                colNames: $config.colNames,
                                colModel: $config.colModel,
                                pager: jQuery($page),
                                rowNum: ($table === 'idx_specs') ? 10 : 20,
                                rowList: [10,20,30],
                                sortname: 'id',
                                sortorder: "desc",
                                viewrecords: true,
                                height: 'auto',
                                width: $width,
                                caption: trans("home_" + $table),
                                resizeStop: function(newwidth,index){
                                    var columnNames = $($idRender)[0].p.colNames;
                                    //newwidth = newwidth /  * 100;
                                    
                                    $.ajax({
                                        url: $base_url + 'backend/home/updateWidth?table=' + $table + '&header=' + columnNames[index] + '&width=' + newwidth,
                                        type: 'get',
                                        async: false
                                    });
                                },
                                onSelectRow: function($rowid){
                                    export_id = $rowid;
                                },
                                ondblClickRow: function($rowid){
                                    if($table === 'idx_specs'){
                                        var $code = $($idRender).getCell($rowid, 'idx_mother');
                                        homeListView.showTable('#list-composition', '#page-composition', $url, 'idx_composition', $width, $code);
                                    }
                                    if($table === 'idx_composition'){
                                        //$idx_code = $($idRender).getCell($rowid, 'idx_code');
                                        $stk_code = $($idRender).getCell($rowid, 'stk_code');
                                        window.location.href = $admin_url + "stk_page/index/" + $stk_code;
                                        //homeListView.showTable('list-compo', '#page-compo', $url_compo, 'idx_compo', $width, $code);
                                    }
                                },
                                gridComplete: function(){
                                    if(typeof res !== 'undefined'){
                                        $($config.font_weight).each(function(i){
                                            $(res.rows).each(function(j){
                                                $id = res.rows[j].id;
                                                $($idRender).setCell($id, $config.font_weight[i],'',{'font-weight': 'bold'});
                                                $($idRender + "_frozen #" + $id + " td[aria-describedby='" + $($idRender).attr("id") + "_" + $config.font_weight[i] + "']").css('font-weight', 'bold');
                                            });
                                        });
                                    }
                                },
                                loadComplete: function(response){
                                    res = response;
                                    homeListView.fixPositionsOfFrozenDivs(this);
                                    $type_button = ["#add", "#edit", "#del"];
                                    $idGrid = $($idRender).attr("id");
                                    $($type_button).each(function(i){
                                        $($type_button[i] + "_" + $idGrid).hide();
                                    });
                                    
                                    $($config.font_weight).each(function(i){
                                        $(response.rows).each(function(j){
                                            $id = response.rows[j].id;
                                            $($idRender).setCell($id, $config.font_weight[i],'',{'font-weight': 'bold'});
                                            $($idRender + "_frozen #" + $id + " td[aria-describedby='" + $($idRender).attr("id") + "_" + $config.font_weight[i] + "']").css('font-weight', 'bold');
                                        });
                                    });

                                    $(response.rows).each(function(i){
                                        $dvar = $($idRender).getCell(response.rows[i]['id'], 'idx_dvar');
                                        if($dvar != undefined && $dvar != ""){
                                            $color = "none";
                                            if($dvar > 0){
                                                $color = "green";
                                            }
                                            if($dvar < 0){
                                                $color = "red";
                                            }
                                            $($idRender).setCell(response.rows[i]['id'], 'idx_dvar', '', {'color': $color});
                                        }
                                    });
                                }
                            });
                            $($idRender).jqGrid('navGrid', $page);
                            $($idRender).jqGrid('filterToolbar');
                            $($idRender).jqGrid('setFrozenColumns');
                            $($idRender + "_frozen tr").live("dblclick", function(){
                                $id = $(this).attr("id");
                                myGrid[0].p.ondblClickRow($id);
                            });
                            $($idRender).jqGrid(
                                'navButtonAdd',
                                $page,
                                {
                                    caption: trans('Export'),
                                    title: trans('Export'),
                                    onClickButton:function(){
                                        if(typeof export_id === "undefined"){
                                            export_id = "";
                                        }
                                        $.ajax({
                                            url: $base_url + 'backend/sysformat/export?table=' + $table + '&id=' + export_id,
                                            type: 'get',
                                            async: false,
                                            success: function(rs){
                                                rs = JSON.parse(rs);
                                                export_id = "";
                                                window.location.href = $admin_url + 'sysformat/download_file?file=' + rs.file + '&path=' + rs.path;
                                            }
                                        });
                                    }
                                }
                            );
                            
                        }
                    });
                }

                getTable();
                

            },
            index: function(){
                /*var a;
                require(['views/sysformat/list'], function(sysformatView){
                    a = sysformatView;
                });*/
                $(document).ready(function(){
                    var $config;
                    var $url = $admin_url + 'home/index';
                    var $table = 'idx_specs';
                    var $width = $(window).width() * 97 / 100;
                    $jsgrdview = homeListView.showTable('#list-specs', '#page-specs', $url, $table);

                });
            },
            render: function(){
                if(typeof this[$app.action] != 'undefined'){
                    new this[$app.action];
                }
            }
        });
        return homeListView = new homeListView;
    });

<div class="block-border">
    <form class="block-content form" id="table_form" method="" action="">
        <h1><?php trans('mn_stats_daily'); ?></h1>
        <div class="no-margin">
            <table class="table no-margin" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="5%" scope="col" sType="numeric" bSortable="true"><?php trans('code'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('sub_code'); ?></th>
                        <th scope="col" sType="string" bSortable="true"><?php trans('name'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('action'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('all'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('hsx'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('hnx'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('upcom'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('stats_daily'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>statistics/daily_all'><?php trans('all'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 15%;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('download_daily'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>download/get_exc'><?php trans('all'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='action' table="vndb_company" href='#'><?php trans('hsx'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='action' table="vndb_company" href='#'><?php trans('hnx'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='action' table="vndb_company" href='#'><?php trans('upcom'); ?></a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('update'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_red' table="vndb_company" href= '<?php admin_url(); ?>statistics/update_daily'><?php trans('all'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 15%;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('report'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_red report' table="vndb_stats_daily" href='javascript:void(0)'><?php trans('view'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 15%;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
<div style="clear:both;">&nbsp;</div>
<div style="clear:both;">&nbsp;</div>
<div style="clear:both;">&nbsp;</div>
<div class="block-border">
    <form class="block-content form" id="table_form" method="" action="">
        <h1><?php trans('mn_stats_monthly'); ?></h1>
        <div class="no-margin">
            <table class="table no-margin" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="5%" scope="col" sType="numeric" bSortable="true"><?php trans('code'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('sub_code'); ?></th>
                        <th scope="col" sType="string" bSortable="true"><?php trans('name'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('action'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('all'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('hsx'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('hnx'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('upcom'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('download_monthly'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('manually'); ?></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_gray' table="vndb_company" href='#'><?php trans('hsx'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_gray' table="vndb_company" href='#'><?php trans('hnx'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_gray' table="vndb_company" href='#'><?php trans('upcom'); ?></a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('stats_monthly'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>statistics/monthly_all'><?php trans('all'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 15%;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('extract_downloaded_data'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>download/get_exc_monthly'><?php trans('all'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='action' table="vndb_company" href='#'><?php trans('hsx'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='action' table="vndb_company" href='#'><?php trans('hnx'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='action' table="vndb_company" href='#'><?php trans('upcom'); ?></a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('update'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_red' table="vndb_company" href='<?php admin_url(); ?>statistics/update_monthly'><?php trans('all'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 15%;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('report'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_red report' table="vndb_stats_monthly" href='javascript:void(0)'><?php trans('view'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 15%;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
<div style="clear:both;">&nbsp;</div>
<div style="clear:both;">&nbsp;</div>
<div style="clear:both;">&nbsp;</div>
<div class="block-border">
    <form class="block-content form" id="table_form" method="" action="">
        <h1><?php trans('mn_stats_yearly'); ?></h1>
        <div class="no-margin">
            <table class="table no-margin" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th width="5%" scope="col" sType="numeric" bSortable="true"><?php trans('code'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('sub_code'); ?></th>
                        <th scope="col" sType="string" bSortable="true"><?php trans('name'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('action'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('all'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('hsx'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('hnx'); ?></th>
                        <th width="7%" scope="col" sType="string" bSortable="true"><?php trans('upcom'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('download_yearly'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('manually'); ?></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_gray' table="vndb_company" href='#'><?php trans('hsx'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_gray' table="vndb_company" href='#'><?php trans('hnx'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_gray' table="vndb_company" href='#'><?php trans('upcom'); ?></a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('stats_yearly'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>statistics/yearly_all'><?php trans('all'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 15%;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('extract_downloaded_data'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>download/get_exc_yearly'><?php trans('all'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='action' table="vndb_company" href='#'><?php trans('hsx'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='action' table="vndb_company" href='#'><?php trans('hnx'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='action' table="vndb_company" href='#'><?php trans('upcom'); ?></a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('update'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_red' table="vndb_company" href='<?php admin_url(); ?>statistics/update_yearly'><?php trans('all'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 15%;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 5%;"></td>
                        <td style="text-align: left; width: 7%;"></td>
                        <td style="text-align: left;"><?php trans('report'); ?></td>
                        <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                        <td style="text-align: center; width: 7%;">
                            <ul class="keywords">
                                <li>
                                    <a class='act_bt_red report' table="vndb_stats_yearly" href='javascript:void(0)'><?php trans('view'); ?></a>
                                </li>
                            </ul>
                        </td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 7%;"></td>
                        <td style="text-align: center; width: 15%;"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
<div style="clear:both;">&nbsp;</div>
<div style="clear:both;">&nbsp;</div>
<div style="clear:both;">&nbsp;</div>
<script>
$(document).ready(function(){
    $(".report").click(function(){
        var table = $(this).attr('table');
        var $widthModal=$('body').width() - 100;
        var html = '<table class="table table-report"></table>';
        var column = new Array();
        openModal('Report', html, $widthModal);
        $.ajax({
            url: $base_url + 'backend/statistics_daily/list_title',
            type: 'post',
            data: 'table=' + table,
            async: false,
            success: function(rs){
                column = JSON.parse(rs);
            }
        });
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
            "iDisplayLength":10,
            "iDisplayStart": 0,
            "bProcessing": true,
            "bRetrieve": true,
            "aaSorting": [],
            // "bAutoWidth": true,
            // "bServerSide": true,
            "sAjaxSource": $admin_url + 'statistics_daily/view_table',
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                aoData.push({
                    "name" : "table",
                    "value" : table
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
            "aoColumns": column,
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
                // this.parent().applyTemplateSetup();
                $(this).slideDown(200);
                // $(".table-report").siblings(".block-controls").children().remove();
                // $(".table-report").siblings(".block-footer").remove();
                // $(".table-report").siblings(".message").remove();

            }
        });


    });
});
</script>
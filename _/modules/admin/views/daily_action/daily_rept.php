<div class="block-border">
    <form class="block-content form" id="table_form" method="" action="">
        <h1><?php trans('mn_daily_action'); ?></h1>
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
                            <td style="text-align: left; width: 5%;"><?php trans('1'); ?></td>
                            <td style="text-align: left; width: 7%;"></td>
                            <td style="text-align: left;"><?php trans('update_calendar'); ?></td>
                            <td style="text-align: left; width: 7%;"></td>
                            <td style="text-align: left; width: 7%;"><?php trans('update'); ?></td>
                            <td style="text-align: center; width: 7%;"><?php trans('hsx'); ?></td>
                            <td style="text-align: center; width: 7%;"><?php trans('hnx'); ?></td>
                            <td style="text-align: center; width: 15%;"><?php trans('upcom'); ?></td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 5%;"><?php trans('2'); ?></td>
                            <td style="text-align: left; width: 7%;"></td>
                            <td style="text-align: left;"><?php trans('download_ref_hsx'); ?></td>
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
                            <td style="text-align: left; width: 5%;"><?php trans('4'); ?></td>
                            <td style="text-align: left; width: 7%;"></td>
                            <td style="text-align: left;"><?php trans('references'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>reference/reference_all'><?php trans('all'); ?></a>
                                    </li>
                                </ul>
                            </td>
                            <td style="text-align: left; width: 7%;"></td>
                            <td style="text-align: left; width: 7%;"></td>
                            <td style="text-align: center; width: 15%;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 5%;"><?php trans('4'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('4_1'); ?></td>
                            <td style="text-align: left;"><?php trans('download_reference_old'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>reference/reference_old'><?php trans('all'); ?></a>
                                    </li>
                                </ul>
                            </td>
                            <td style="text-align: left; width: 7%;"></td>
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
                            <td style="text-align: left; width: 5%;"><?php trans('4'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('4_2'); ?></td>
                            <td style="text-align: left;"><?php trans('download_reference_new'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>download/action'><?php trans('all'); ?></a>
                                    </li>
                                </ul>
                            </td>
                            <td style="text-align: left; width: 7%;"></td>
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
                            <td style="text-align: left; width: 5%;"><?php trans('4'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('4_3'); ?></td>
                            <td style="text-align: left;"><?php trans('references_merges'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>reference/reference_merges'><?php trans('all'); ?></a>
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
                            <td style="text-align: left; width: 5%;"><?php trans('4'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('4_4'); ?></td>
                            <td style="text-align: left;"><?php trans('references_export'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>reference/reference_export'><?php trans('all'); ?></a>
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
                            <td style="text-align: left; width: 5%;"><?php trans('4'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('4_5'); ?></td>
                            <td style="text-align: left;"><?php trans('report'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>reference/report_all'><?php trans('all'); ?></a>
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
                            <td style="text-align: left; width: 5%;"><?php trans('5'); ?></td>
                            <td style="text-align: left; width: 7%;"></td>
                            <td style="text-align: left;"><?php trans('switch_references'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>reference/reference_switch'><?php trans('all'); ?></a>
                                    </li>
                                </ul>
                            </td>
                            <td style="text-align: center; width: 7%;"></td>
                            <td style="text-align: center; width: 7%;"></td>
                            <td style="text-align: center; width: 7%;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 5%;"><?php trans('6'); ?></td>
                            <td style="text-align: left; width: 7%;"></td>
                            <td style="text-align: left;"><?php trans('download_other_reference_automatically_hsx_hnx_upcom'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>download/get_shares_caf'><?php trans('all'); ?></a>
                                    </li>
                                </ul>
                            </td>
                            <td style="text-align: center; width: 7%;"></td>
                            <td style="text-align: center; width: 7%;"></td>
                            <td style="text-align: center; width: 7%;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 5%;"><?php trans('7'); ?></td>
                            <td style="text-align: left; width: 7%;"></td>
                            <td style="text-align: left;"><?php trans('prices'); ?></td>
                            <td style="text-align: left; width: 7%;"></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>prices/prices_all'><?php trans('all'); ?></a>
                                    </li>
                                </ul>
                            </td>
                            <td style="text-align: center; width: 7%;"></td>
                            <td style="text-align: center; width: 7%;"></td>
                            <td style="text-align: center; width: 7%;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 5%;"><?php trans('7'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('7_1'); ?></td>
                            <td style="text-align: left;"><?php trans('download_prices_automatically'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>prices'><?php trans('all'); ?></a>
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
                            <td style="text-align: left; width: 5%;"><?php trans('7'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('7_2'); ?></td>
                            <td style="text-align: left;"><?php trans('update_report'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>prices/report_all'><?php trans('all'); ?></a>
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
                            <td style="text-align: left; width: 5%;"><?php trans('8'); ?></td>
                            <td style="text-align: left; width: 7%;"></td>
                            <td style="text-align: left;"><?php trans('daily'); ?></td>
                            <td style="text-align: left; width: 7%;"></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>daily/both_merges_switch'><?php trans('all'); ?></a>
                                    </li>
                                </ul>
                            </td>
                            <td style="text-align: center; width: 7%;"></td>
                            <td style="text-align: center; width: 7%;"></td>
                            <td style="text-align: center; width: 7%;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 5%;"><?php trans('8'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('8_1'); ?></td>
                            <td style="text-align: left;"><?php trans('switch_prices'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>prices/prices_switch'><?php trans('all'); ?></a>
                                    </li>
                                </ul>
                            </td>
                            <td style="text-align: center; width: 7%;"></td>
                            <td style="text-align: center; width: 7%;"></td>
                            <td style="text-align: center; width: 7%;"></td>
                        </tr>
                        <tr>
                            <td style="text-align: left; width: 5%;"><?php trans('8'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('8_2'); ?></td>
                            <td style="text-align: left;"><?php trans('merge_and_export_data'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url(); ?>daily/merges_daily'><?php trans('all'); ?></a>
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
                            <td style="text-align: left; width: 5%;"><?php trans('8'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('8_3'); ?></td>
                            <td style="text-align: left;"><?php trans('report_daily'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="vndb_company" href='<?php echo admin_url() . 'report/report_daily' ?>'><?php trans('all'); ?></a>
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
                            <td style="text-align: left; width: 5%;"><?php trans('8'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('8_4'); ?></td>
                            <td style="text-align: left;"><?php trans('switch_daily'); ?></td>
                            <td style="text-align: left; width: 7%;"><?php trans('automatically'); ?></td>
                            <td style="text-align: center; width: 7%;">
                                <ul class="keywords">
                                    <li>
                                        <a class='act_bt_red' table="" href='<?php echo admin_url(); ?>daily/daily_switch'><?php trans('all'); ?></a>
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
                    </tbody>
            </table>
        </div>
    </form>

</div>
<div style="clear:both;">&nbsp;</div>
<div style="clear:both;">&nbsp;</div>
<div style="clear:both;">&nbsp;</div>
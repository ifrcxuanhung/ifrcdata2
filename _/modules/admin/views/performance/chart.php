<style type="text/css">
.dataTables_scrollHeadInner{
        background: -moz-linear-gradient(center top , #CCCCCC, #A4A4A4) repeat scroll 0 0 transparent;
    border-color: white #999999 #828282 #DDDDDD;
    border-style: solid;
    border-width: 1px;
    color: white;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.7);
}
.dataTables_scrollHeadInner table{
    display: block !important;
    margin-top: -1px;
    height:25px;
}
</style>
<section id="compare-currency" class="grid_12">
    <div class="block-border" style="float:left; width:100%">
        <form class="block-content form-table-ajax" id="" method="post" action="javascript:void(0)" style="float:left; width:96.5%">
            <h1><?php trans('mn_chart'); ?></h1>
            <div style="float:right; text-align:right; width:100%">
                Code <select name="code_mother" style="padding:5px; margin-right:10px">
                    <?php
                        foreach($data_code as $code){
                            echo '<option name="' . $code['idx_mother'] . '">' . $code['idx_mother'] . '</option>';
                        }
                    ?>
                    </select>
                Currency <select name="currency" style="padding:5px; margin-right:10px">
                    <?php
                        foreach($data_currency as $currency){
                            echo '<option name="' . $currency['idx_curr'] . '">' . $currency['idx_curr'] . '</option>';
                        }
                    ?>
                    </select>
                Type <select name="type" style="padding:5px; margin-right:10px">
                    <?php
                        foreach($data_type as $type){
                            echo '<option name="' . $type['type'] . '">' . $type['type'] . '</option>';
                        }
                    ?>
                    </select>
                <button class="excute">Excute</button>
            </div>
            <div id="tab_chart" style="display:none; width:100%; float:left" >
                <div id="tab-global" class="tabs-content" style="width:44%;float:left; margin-right:20px; position:relative">
                    <ul class="tabs js-tabs same-height">
                        <li class="current"><a href="#day" class="get_table" id="q_index_vnx_daily">Day</a></li>
                        <li><a href="#month" class="get_table" id="q_index_vnx_monthly">Month</a></li>
                        <li><a href="#year" class="get_table" id="q_index_vnx_yearly">Year</a></li>
                    </ul>
                    <div class="tabs-content">
                        <div id="day" class="css_test">
                                <table class="table table-ajax" id="tab_q_index_vnx_daily" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="25%" scope="col" sType="string" bSortable="true">
                                                <span class="column-sort">
                                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                                </span>
                                                <?php trans('code'); ?>
                                            </th>
                                            <th width="25%" cope="col" sType="string" bSortable="true">
                                                <span class="column-sort">
                                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                                </span>
                                                <?php trans('date'); ?>
                                            </th>
                                            <th width="25%" scope="col" sType="numeric" bSortable="true">
                                                <span class="column-sort">
                                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                                </span>
                                                <?php trans('close'); ?>
                                            </th>
                                            <th width="25%" scope="col" sType="numeric" bSortable="true">
                                                <span class="column-sort">
                                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                                </span>
                                                <?php trans('perform'); ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                        </div>
                        <div id="month" class="css_test">
                            <table class="table table-ajax" id="tab_q_index_vnx_monthly" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="25%" scope="col" sType="string" bSortable="true">
                                            <span class="column-sort">
                                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                            </span>
                                            <?php trans('code'); ?>
                                        </th>
                                        <th width="25%" cope="col" sType="string" bSortable="true">
                                            <span class="column-sort">
                                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                            </span>
                                            <?php trans('date'); ?>
                                        </th>
                                        <th width="25%" scope="col" sType="string" bSortable="true">
                                            <span class="column-sort">
                                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                            </span>
                                            <?php trans('close'); ?>
                                        </th>
                                        <th width="25%" scope="col" sType="numeric" bSortable="true">
                                            <span class="column-sort">
                                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                            </span>
                                            <?php trans('perform'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="year" class="css_test">
                            <table class="table table-ajax" id="tab_q_index_vnx_yearly" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="25%" scope="col" sType="string" bSortable="true">
                                            <span class="column-sort">
                                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                            </span>
                                            <?php trans('code'); ?>
                                        </th>
                                        <th width="25%" cope="col" sType="string" bSortable="true">
                                            <span class="column-sort">
                                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                            </span>
                                            <?php trans('date'); ?>
                                        </th>
                                        <th width="25%" scope="col" sType="string" bSortable="true">
                                            <span class="column-sort">
                                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                            </span>
                                            <?php trans('close'); ?>
                                        </th>
                                        <th width="25%" scope="col" sType="numeric" bSortable="true">
                                            <span class="column-sort">
                                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                            </span>
                                            <?php trans('perform'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="chart" style="width:50%; float:left">
                    <div id="container" style="width: 100%; height: 435px; margin: 0 auto"></div>
                </div>
            </div>
        </form>
    </div>
</section>
<style type="text/css">
    .css_test{
        height:auto !important;
    }
</style>
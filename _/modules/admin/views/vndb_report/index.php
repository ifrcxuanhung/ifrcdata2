<style>
    .table-report {
        width: 100% !important;
    }
</style>
<div class="disable-form" style="display: none"><div class="my-progress"></div></div>
<article id="custom-hnx" class="fix_auto">
    <section class="grid_10">
        <div class="block-border">
            <div class="block-content">
                <h1>VNDB Daily Report</h1>
                <div class="block-controls">
                    <div class="custom-btn fx_bt_indi red">
                        <button type="button" class="" onclick="window.location.href='<?php echo admin_url(); ?>'">Cancel</button>
                        <div style="clear: left;"></div>
                    </div>
                    <div class="custom-btn fx_bt_indi ">
                        <button type="button" id="save">Execute</button>
                        <div style="clear: left;"></div>
                    </div>
                </div>
                <form action="#" class="block-content form form_fx">
                    <ul class="blocks-list">

                        <li>
                            <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">From</a>
                            <div class="columns">
                                <p class="colx2-right">
                                    <input type="text" name="startdate" id="startdate" value="" style="width: 60%"> <img width="16" height="16" src="<?php echo base_url(); ?>assets/templates/backend/images/icons/fugue/calendar-month.png">
                                </p>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">To</a>
                            <div class="columns">
                                <p class="colx2-right">
                                    <input type="text" name="enddate" id="enddate" value="" style="width: 60%"> <img width="16" height="16" src="<?php echo base_url(); ?>assets/templates/backend/images/icons/fugue/calendar-month.png">
                                </p>
                            </div>
                        </li>

                    </ul>
                </form>


            </div></div>
    </section>

</article>
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1>REPORT</h1>

            <div class="custom-btn" style="display:none;float: right; z-index: 200; position: relative;">
                <div style="clear: left;"></div>
            </div>
            <table class="table table-report table-ajax" cellspacing="0" width="100%" style="display: table">
                <thead>
                    <tr>
                        <th width="20%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('Task'); ?>
                        </th>
                        <th width="16%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('Row'); ?>
                        </th>
                        <th width="16%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('last=0'); ?>
                        </th>
                        <th width="16%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('shli=0'); ?>
                        </th>
                        <th width="16%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('shou=0'); ?>
                        </th>
                        <th width="16%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('adj_cls=0'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </form>
    </div>
</section>

<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1><?php trans('new_company'); ?></h1>
            <div>
                <?php
                if (isset($new_company) && is_array($new_company) && count($new_company) > 0) {
                    foreach ($new_company as $value) {
                        echo $value['ticker'] . ' , ';
                    }
                }
                ?>
            </div>
        </form>
    </div>
</section>
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1>SHARES CHANGE</h1>

            <div class="custom-btn" style="display:none;float: right; z-index: 200; position: relative;">
                <div style="clear: left;"></div>
            </div>
            <table class="table table-shares table-ajax" cellspacing="0" width="100%" style="display: table">
                <thead>
                    <tr>
                        <th width="20%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('Date'); ?>
                        </th>
                        <th width="20%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('Ticker'); ?>
                        </th>
                        <th width="20%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('Market'); ?>
                        </th>
                        <th width="20%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('shli change'); ?>
                        </th>
                        <th width="20%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('shou change'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </form>
    </div>
</section>
<article class="fix_auto">
    <section class="grid_10">
        <div class="block-border">
            <div class="block-content">
                <h1>Divident</h1>
                <div class="block-controls">
                    <div class="custom-btn fx_bt_indi ">
                        <button type="button" class="" onclick="$('.form').submit();">Save</button>
                        <div style="clear: left;"></div>
                    </div>
                </div>
                <form action="" method="post" class="block-content form form_fx">
                    <ul class="blocks-list">

                        <li>
                            <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Table</a>
                            <div class="columns">
                                <p class="colx2-right">
                                    <input type="text" name="table" id="complex-fr-subtitle" value="" class="full-width">
                                </p>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Column</a>
                            <div class="columns">
                                <p class="colx2-right">
                                    <input type="text" name="column" id="complex-fr-subtitle" value="" class="full-width">
                                </p>
                            </div>
                        </li>

                    </ul>
                </form>
            </div>
        </div>
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
                        <th width="" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('Ticker'); ?>
                        </th>
                        <th width="" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('date'); ?>
                        </th>
                        <th width="" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('pdate'); ?>
                        </th>
                        <th width="" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('ndate'); ?>
                        </th>
                        <th width="" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('value'); ?>
                        </th>
                        <th width="" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('pvalue'); ?>
                        </th>
                        <th width="" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('nvalue'); ?>
                        </th>
                        <th width="" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('fieldname'); ?>
                        </th>
                        <th width="" scope="col" sType="date" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('t1'); ?>
                        </th>
                        <th width="" scope="col" sType="date" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('t2'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </form>
    </div>
</section>
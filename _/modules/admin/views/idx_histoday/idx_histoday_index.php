<article>
    <section class="grid_12">
        <div class="block-border">
            <form class="block-content form" id="table_form" method="post" action="">
                <h1>Idx_histoday</h1>
                <div class="no-margin">

                    <table class="table sortable no-margin" cellspacing="0" width="100%" height='200'>
                        <thead>
                            <tr>
                                <th width="15%" scope="col" sType="numeric" bSortable="true" style="text-align: left;">
                                    Last
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                                <th width="15%" scope="col" sType="numeric" bSortable="true" style="text-align: right;">
                                    Changes
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                                    %var
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                                    Pclose
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                                <th width="5%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                                    Dvar
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                                <th width="25%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                                    Time
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (is_array($histoday)) {
                                foreach ($histoday as $item) {
                                    ?>
                                    <tr>
                                        <td style="text-align: right;"><?php echo number_format($item['last']); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($item['changes']); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($item['var']); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($item['pclose']); ?></td>
                                        <td style="text-align: right;"><?php echo number_format($item['dvar']); ?></td>
                                        <td style="text-align: right;"><?php echo $item['times']; ?></td>

                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </section>
    <section class="grid_12">
        <div class="block-border">
            <form class="block-content form" id="table_form" method="post" action="">
                <h1>Idx_composition</h1>
                <div class="no-margin">

                    <table class="table sortable no-margin" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th scope="col" sType="numeric" bSortable="true" style="text-align: left; width: 10%;">
                                    stk-code
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                                <th scope="col" sType="numeric" bSortable="true" style="text-align: left; width: 10%;">
                                    idx-code
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                                <th width="15%" scope="col" sType="numeric" bSortable="true" style="text-align: left;">
                                    stk-name
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                                <th width="10%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                                    Shares
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                                <th width="10%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                                    Market cap
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                                    Capp
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                                <th width="25%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                                    Float
                                    <span class="column-sort">
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>
                                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                                    WGT
                                    <span class="column-sort" >
                                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                    </span>
                                </th>


                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (is_array($composition)) {
                                foreach ($composition as $item) {
                                    ?>
                                    <tr>
                                        <td width="10%" style="text-align: left; vertical-align: middle;"><a href="<?php echo admin_url() . 'stk_page/index/' . $item['stk_code']; ?>"><?php echo $item['stk_code']; ?></a></td>
                                        <td width="10%" style="text-align: left; vertical-align: middle;"><?php echo $item['idx_code']; ?></td>
                                        <td width="30%" style="text-align: left; vertical-align: middle;"><?php echo $item['stk_name']; ?></td>
                                        <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo number_format($item['stk_shares_idx']); ?></td>
                                        <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo number_format($item['stk_mcap_idx']); ?></td>
                                        <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo number_format($item['stk_capp_idx'], $decimal['stk_capp_idx']); ?></td>
                                        <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo number_format($item['stk_float_idx']); ?></td>
                                        <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo number_format($item['stk_wgt'], 1); ?>0</td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </section>
    <div class="clear"></div>
</article>
<script>
    $(document).live("ready", function(){
        $(".block-footer .float-left").remove();
    })
</script>
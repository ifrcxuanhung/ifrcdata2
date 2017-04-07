<style>
    .table-report {
        width: 100% !important;
    }
</style>
<div class="disable-form" style="display: none"><div class="my-progress"></div></div>
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1>Compare Prices</h1>

            <div class="custom-btn" style="display:none;float: right; z-index: 200; position: relative;">
                <div style="clear: left;"></div>
            </div>
            <table class="table table-shares table-ajax" cellspacing="0" width="100%" style="display: table">
                <thead>
                    <tr>
                    <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('Ticker'); ?>
                        </th>
                        <th width="10%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('Date'); ?>
                        </th>
                        <th width="10%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('Market'); ?>
                        </th>
                        
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('vlm'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('vlm_diff'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('pcls_diff'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('last_diff'); ?>
                        </th>
                        <!--<th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('strn_vndb'); ?>
                        </th>-->
                    </tr>
                </thead>
                <tbody>
            <?php
                if(is_array($difference)){
                    foreach($difference as $item){
                ?>    
                    <tr>
                        <td><?php echo $item['ticker']; ?></td>
                        <td><?php echo $item['date']; ?></td>
                        <td><?php echo $item['market']; ?></td>
                        <td><?php echo $item['vlm']; ?></td>
                        <td><?php echo $item['vlm_diff']; ?></td>
                        <td><?php echo $item['pcls_diff']; ?></td>
                        <td><?php echo $item['last_diff']; ?></td>
                    </tr>
                <?php
                    }
                }
            ?>
                </tbody>
            </table>
        </form>
    </div>
</section>


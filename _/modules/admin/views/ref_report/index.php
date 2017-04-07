<style>
    .table-report {
        width: 100% !important;
    }
</style>
<div class="disable-form" style="display: none"><div class="my-progress"></div></div>
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1>Change Day</h1>

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
                            <?php trans('Pdate'); ?>
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
                            <?php trans('shli'); ?>
                        </th>
                        <th width="20%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('pshli'); ?>
                        </th>
                        <th width="20%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('shou'); ?>
                        </th>
                        <th width="20%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('pshou'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
            <?php
                if(is_array($changes)){
                    foreach($changes as $item){
                ?>    
                    <tr>
                        <td><?php echo $item['date']; ?></td>
                        <td><?php echo $item['pdate']; ?></td>
                        <td><?php echo $item['ticker']; ?></td>
                        <td><?php echo $item['market']; ?></td>
                        <td><?php echo $item['shli']; ?></td>
                        <td><?php echo $item['pshli']; ?></td>
                        <td><?php echo $item['shou']; ?></td>
                        <td><?php echo $item['pshou']; ?></td>
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
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1>Anomalies</h1>

            <div class="custom-btn" style="display:none;float: right; z-index: 200; position: relative;">
                <div style="clear: left;"></div>
            </div>
            <table class="table table-shares table-ajax" cellspacing="0" width="100%" style="display: table">
                <thead>
                    <tr>
                        <th width="10%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('Date'); ?>
                        </th><th width="10%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('correct'); ?>
                        </th>
                        <th width="10%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('txtrefhsx'); ?>
                        </th>
                        <th width="10%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('txtrefhnx'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('txtrefupc'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('txtrefall'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('txtrefsum'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('tblref'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('shli'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('shou'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
            <?php
                if(is_array($anomalies)){
                    foreach($anomalies as $item){
                ?>    
                    <tr>
                        <td><?php echo $item['date']; ?></td>
                        <td><?php echo $item['correct']; ?></td>
                        <td><?php echo $item['txtrefhsx']; ?></td>
                        <td><?php echo $item['txtrefhnx']; ?></td>
                        <td><?php echo $item['txtrefupc']; ?></td>
                        <td><?php echo $item['txtrefall']; ?></td>
                        <td><?php echo $item['txtrefsum']; ?></td>
                        <td><?php echo $item['tblref']; ?></td>
                        <td><?php echo $item['shli0']; ?></td>
                        <td><?php echo $item['shou0']; ?></td>
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
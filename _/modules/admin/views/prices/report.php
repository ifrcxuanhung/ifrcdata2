<style>
    .table-report {
        width: 100% !important;
    }
</style>
<div class="disable-form" style="display: none"><div class="my-progress"></div></div>
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1>Stat Daily</h1>

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
                            <?php trans('correct'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('correct_vlm'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('correct_trn'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('slvm_vndb'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('strn_vndb'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('slvm_exc'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('strn_exc'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('nb'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('svlm_diff'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('strn_diff'); ?>
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
                        <td><?php echo $item['market']; ?></td>
                        <td><?php echo $item['correct']; ?></td>
                        <td><?php echo $item['correct_vlm']; ?></td>
                        <td><?php echo $item['correct_trn']; ?></td>
                        <td><?php echo $item['svlm_vndb']; ?></td>
                        <td><?php echo $item['strn_vndb']; ?></td>
                        <td><?php echo $item['svlm_exc']; ?></td>
                        <td><?php echo $item['strn_exc']; ?></td>
                        <td><?php echo $item['nb']; ?></td>
                        <td><?php echo $item['svlm_diff']; ?></td>
                        <td><?php echo $item['strn_diff']; ?></td>
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
                            <?php trans('txtprchsx'); ?>
                        </th>
                        <th width="10%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('txtprchnx'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('txtprcupc'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('txtprcall'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('txtprcsum'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('tblprc'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('last'); ?>
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
                        <td><?php echo $item['txtprchsx']; ?></td>
                        <td><?php echo $item['txtprchnx']; ?></td>
                        <td><?php echo $item['txtprcupc']; ?></td>
                        <td><?php echo $item['txtprcall']; ?></td>
                        <td><?php echo $item['txtprcsum']; ?></td>
                        <td><?php echo $item['tblprc']; ?></td>
                        <td><?php echo $item['last0']; ?></td>
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
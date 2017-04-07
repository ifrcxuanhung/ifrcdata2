<article class="container_12" style="position:relative; margin: 0px ! important; width: 100%;">
    <section class="grid_4" style="width:22% !important">
        <div class="block-border">
            <div class="block-content">

                <h1><?php trans('mn_event_type'); ?></h1>
                <div class="block-controls">
                    <ul class="controls-buttons">
                        <li></li>
                        <li></li>
                    </ul>
                </div>
                <div id="loading"><img src="<?php echo base_url() . 'assets/images/loading.gif'; ?>" /></div>
                <ul id="menu" class="collapsible-list with-bg">
                    <li><input type="checkbox" class="selAllChksInGroup">&nbsp;All</li>
                    <?php
                        foreach($data_type as $dt_k => $dt_v){
                            if($dt_v['EVENT_TYPE'] == 'OTHER'){
                                unset($data_type[$dt_k]);
                            }else{
                    ?>
                        <li>
                            <input type="checkbox" name="stats-display[]" value="<?= $dt_v['EVENT_TYPE'] ?>">
                            <?= $dt_v['EVENT_TYPE'] ?>
                        </li>
                    <?php
                            }
                        }
                    ?>
                    <li><input type="checkbox" name="stats-display[]" value="OTHER">&nbsp;OTHER</li>
                </ul>
                <button class="blue" id="filter" ids="" style="position:absolute; bottom:5px; right:5px">Filter</button>
            </div>
        </div>
    </section>
    <section class="grid_8" style="display:none; width:74% !important">
        <div class="block-border">
            <form class="block-content form-table-ajax" id="" method="post" action="">
                <h1><?php trans('mn_events'); ?></h1>

                <table id="vndb_events_day" class="table table-events-list table-ajax" table="vndb_events_day" cellspacing="0" width="100%" style="display: table">
                    <thead>
                        <tr>
                            <th scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('ticker'); ?>
                            </th>
                            <th scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('market'); ?>
                            </th>
                            <th scope="col" sType="numeric" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('date_ann') ?>
                            </th>
                            <th scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('event_type') ?>
                            </th>
                            <th scope="col" sType="numeric" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('evname') ?>
                            </th>
                            <th scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('content') ?>
                            </th>
                    </thead>
                    <tbody>
            
                    </tbody>
                </table>
            </form>
        </div>
        <div id="event-dialog" style="font-size: 14px"></div>
    </section>
</article>
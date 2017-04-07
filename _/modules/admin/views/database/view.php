<style type="text/css">
div.dataTables_scroll { 
    clear: both; 
    margin-top: -1.667em;
}
.dataTables_scrollHeadInner{
    background: -moz-linear-gradient(center top , rgb(204, 204, 204), rgb(164, 164, 164)) repeat scroll 0px 0px transparent; 
    border-top: 1px solid white;
    margin-top: -1px;
}
.dataTables_scrollHeadInner table{
    display: block !important;
    height:50px;
    margin-top: -2px;
}
</style>
<section class="grid_12">
    <div class="block-border">
        <div class="block-content" style="padding-top: 30px;">
            <h1><?php trans('mn_structure'); ?></h1>
            <button type="button" class="action-clean" action="structure" style="float:right; margin-left:10px; margin-top:-5px"><?php trans('bt_clean'); ?></button>     
            <table class="table sortable no-margin" cellspacing="0" width="100%" pagination="false">
                <thead>
                    <tr>
                        <th scope="col" width="5%" sType="string" bSortable="true">
                            <?php trans('database'); ?>
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                        </th>
                        <th scope="col" width="5%" sType="string" bSortable="true">
                            <?php trans('table'); ?>
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                        </th>
                        <th scope="col" width="5%" sType="string" bSortable="true">
                            <?php trans('field'); ?>
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                        </th>
                        <th scope="col" width="10%" sType="string" bSortable="true">
                            <?php trans('type') ?>
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                        </th>
                        <th scope="col" width="10%" sType="string" bSortable="true">
                            <?php trans('null') ?>
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                        </th>
                        <th scope="col" width="10%" sType="string" bSortable="true">
                            <?php trans('key') ?>
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                        </th>
                        <th scope="col" width="10%" sType="string" bSortable="true">
                            <?php trans('default') ?>
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                        </th>
                        <th scope="col" width="10%" sType="string" bSortable="true">
                            <?php trans('extra') ?>
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($infoStructure as $itemS){
                    ?>
                        <tr>
                            <td><?= $itemS['database'] ?></td>
                            <td><?= $itemS['table'] ?></td>
                            <td><?= $itemS['field'] ?></td>
                            <td><?= $itemS['type'] ?></td>
                            <td><?= $itemS['null'] ?></td>
                            <td><?= $itemS['key'] ?></td>
                            <td><?= $itemS['default'] ?></td>
                            <td><?= $itemS['extra'] ?></td>
                        </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<section class="grid_12">
    <div class="block-border">
        <div class="block-content" style="padding-top: 30px;">
            <h1><?php trans('mn_index'); ?></h1>
            <button type="button" class="action-clean" action="index" style="float:right; margin-left:10px; margin-top:-5px"><?php trans('bt_clean'); ?></button>     
            <table class="table sortable no-margin" cellspacing="0" width="100%" pagination="false">
                <thead>
                    <tr>
                        <th scope="col" width="5%" sType="string" bSortable="true">
                            <?php trans('database'); ?>
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                        </th>
                        <th scope="col" width="5%" sType="string" bSortable="true">
                            <?php trans('table'); ?>
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                        </th>
                        <th scope="col" width="5%" sType="string" bSortable="true">
                            <?php trans('group'); ?>
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                        </th>
                        <th scope="col" width="10%" sType="string" bSortable="true">
                            <?php trans('item') ?>
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($infoIndex as $itemI){
                    ?>
                        <tr>
                            <td><?= $itemI['database'] ?></td>
                            <td><?= $itemI['table'] ?></td>
                            <td><?= $itemI['group'] ?></td>
                            <td><?= $itemI['item'] ?></td>
                        </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
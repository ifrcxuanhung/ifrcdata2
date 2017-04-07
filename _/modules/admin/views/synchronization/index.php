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
<form name="formImport" class="form" id="complex_form" method="post" action=""  enctype="multipart/form-data" >
    <section class="grid_12">
        <div class="block-border">
            <div class="block-content" style="padding-top: 30px;">
                <h1><?php trans('mn_synchronization'); ?></h1>
                <button type="button" class="action-synchronization" style="float:right; margin-left:10px; margin-top:-5px"><?php trans('bt_synchronization'); ?></button>     
                <table class="table sortable no-margin" cellspacing="0" width="100%" pagination="false">
                    <thead>
                        <tr>
                            <th scope="col" width="15%" sType="string" bSortable="true">
                                <?php trans('from_db'); ?>
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                            </th>
                            <th scope="col" width="15%" sType="string" bSortable="true">
                                <?php trans('from_tb'); ?>
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                            </th>
                            <th scope="col" width="15%" sType="string" bSortable="true">
                                <?php trans('to_db'); ?>
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                            </th>
                            <th scope="col" width="10%" sType="string" bSortable="true">
                                <?php trans('to_table') ?>
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
                                <?php trans('check') ?>
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($info as $item){
                        ?>
                            <tr>
                                <td scope="col" width="15%"><?= $item['from_db'] ?></td>
                                <td scope="col" width="15%"><?= $item['from_tb'] ?></td>
                                <td scope="col" width="15%"><?= $item['to_db'] ?></td>
                                <td scope="col" width="10%"><?= $item['to_tb'] ?></td>
                                <td scope="col" width="10%"><?= $item['type'] ?></td>
                                <td scope="col" width="10%"><input type="checkbox" name="check[]" value="<?= $item['id'] ?>" /></td>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</form>
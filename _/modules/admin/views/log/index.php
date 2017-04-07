<style type="text/css">
.dataTables_scrollHeadInner{
    background: -moz-linear-gradient(center top , #B5B5B5, #757474) repeat scroll 0 0 transparent;
}
</style>
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <a href="#"><h1><?php trans('mn_log'); ?></h1></a>
            <table class="table sortable table-ajax" cellspacing="0" width="100%" style="display: table" pagination="false" search="false" footer="false">
                <thead>
                    <tr>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('date'); ?>
                        </th>
                        <th width="6%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('user'); ?>
                        </th>
                        <th width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('link'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($info as $item){
                    ?>
                        <tr>
                            <td><?= $item['date'] ?></td>
                            <td><?= $item['user'] ?></td>
                            <td><?= $item['url'] ?></td>
                        </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </form>
    </div>
</section>
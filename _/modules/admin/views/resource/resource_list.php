<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1><?php trans('mn_resource'); ?></h1>

            <div style="float: right; margin-right: 20px;" class="custom-btn">
                <button onclick="$(location).attr('href','<?php echo admin_url() . 'resource/add' ?>');" style="float: left; height:30px;" type="button"><?php trans('bt_add'); ?></button>
                <div style="clear: left;"></div>
            </div>

            <div class="no-margin">
                <table class="table table-resource-list table-ajax" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="5%" scope="col" sType="numeric" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('no_'); ?>
                            </th>
                            <th width="25%" scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('module') ?>
                            </th>
                            <th width="25%"scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('controller') ?>
                            </th>
                            <th scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('action') ?>
                            </th>
                            <th width="15%" scope="col" class="table-actions"><?php trans('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</section>
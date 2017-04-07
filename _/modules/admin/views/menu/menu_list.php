<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1><?php trans('mn_menu'); ?></h1>
            <div class="custom-btn" style="display:none;float: right; z-index: 200; position: relative;">
                <button onclick="$(location).attr('href','<?php echo admin_url() . 'menu/add' ?>');" class="" type="button"><?php trans('bt_add'); ?></button>
                <div style="clear: left;"></div>
            </div>

            <table class="table table-menu-list table-ajax" cellspacing="0" width="100%" style="display: table">
                <thead>
                    <tr>
                        <th width="8%" scope="col" sType="numeric" bSortable="false">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('no_'); ?>
                        </th>
                        <th width="30%" scope="col" sType="string" bSortable="false">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('name'); ?>
                        </th>
                        <th scope="col" sType="numeric" bSortable="false">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('url'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="false">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('order'); ?>
                        </th>
                        <th width="10%" scope="col" sType="string" bSortable="false">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('Active'); ?>
                        </th>
                        <th width="15%" scope="col" class="table-actions" sType="" bSortable="false"><?php trans('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </form>
    </div>
</section>
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1><?php trans('mn_category'); ?></h1>
            <div class="custom-btn" style="display:none;float: right; z-index: 200; position: relative;">
                <?php
                if (isset($list_category_selectbox) && is_array($list_category_selectbox)):
                    echo form_dropdown('category', unshift($list_category_selectbox, '0', 'Select categories (All) '), $this->uri->segment(4), 'class="float-left margin-right padding-child-2-5"');
                endif;
                ?>
                <button onclick="$(location).attr('href','<?php echo admin_url() . 'category/add' ?>');" class="" type="button"><?php trans('bt_add'); ?></button>
                <div style="clear: left;"></div>
            </div>
            <div id="loading"><img src="<?php echo base_url() . 'assets/images/loading.gif' ;?>" /></div>
            <table class="table table-category-list table-ajax" cellspacing="0" width="100%" style="display: table">
                <thead>
                    <tr>
                        <th width="8%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('no_'); ?>
                        </th>
                        <th scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('name'); ?>
                        </th>
                        <th width="7%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('order'); ?>
                        </th>
                        <th width="7%" scope="col" sType="string" bSortable="false">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('status'); ?>
                        </th>
                        <th width="6%" scope="col"><?php trans('image'); ?></th>
                        <th width="15%" scope="col"><?php trans('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </form>
    </div>
</section>
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1><?php trans('mn_media'); ?></h1>

            <div style="float: right; margin-right: 20px;" class="custom-btn">
                <select id="media-category" name="media-category" class="float-left margin-right padding-child-2-5">
                    <option value="0"><?php trans('Select categories (All) ') ?></option>
                    <?php
                    if (isset($list_category) == TRUE && count($list_category) > 0) {
                        foreach ($list_category as $key => $value) {
                            echo "<option value='" . $key . "'>" . $value . "</option>";
                        }
                    }
                    ?>
                </select>
                <button onclick="$(location).attr('href','<?php echo admin_url() . 'media/add' ?>');" class="" type="button"><?php trans('bt_add'); ?></button>
                <div style="clear: left;"></div>
            </div>
            <div id="loading"><img src="<?php echo base_url() . 'assets/images/loading.gif'; ?>" /></div>
            <div class="no-margin">
                <table class="table table-media-list table-ajax" cellspacing="0" width="100%" style="display: table">
                    <thead>
                        <tr>
                            <th width="8%" scope="col" sType="numeric" bSortable="false">
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
                                <?php trans('title'); ?>
                            </th>
                            <th width="15%" scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('description'); ?>
                            </th>
                            <th width="10%" scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('category'); ?>
                            </th>
                            <th width="7%" scope="col" sType="numeric" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('order'); ?>
                            </th>
                            <th width="10%" scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('type'); ?>
                            </th>
                            <th width="7%" scope="col"><?php trans('status'); ?></th>
                            <th width="6%" scope="col"><?php trans('image'); ?></th>
                            <th width="15%%" scope="col" class="table-actions"><?php trans('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </form>
    </div>
</section>
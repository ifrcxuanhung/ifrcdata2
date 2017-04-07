<section class="grid_12">
    <div class="block-border">
        <form class="block-content form" id="table_form" method="post" action="">
            <h1><?php trans('mn_setting'); ?></h1>

            <div style="float: right; margin-right: 20px;" class="custom-btn">
                <button onclick="$(location).attr('href','<?php echo admin_url() . 'setting/add' ?>');" style="float: left; height:30px;" type="button"><?php trans('bt_add'); ?></button>
                <div style="clear: left;"></div>
            </div>

            <div class="no-margin">
                <table class="table sortable no-margin" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="5%" scope="col" sType="numeric" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('no_'); ?>
                            </th>
                            <th width="20%" scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('key'); ?>
                            </th>
                            <th scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('value'); ?>
                            </th>
                            <th width="12%" scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('group'); ?>
                            </th>
                            <th width="15%" scope="col" class="table-actions"><?php trans('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($list_setting):
                            foreach ($list_setting as $value):
                                ?>
                                <tr>
                                    <td style="text-align: left; width: 5%;"><?php echo $value['setting_id']; ?></td>
                                    <td style="text-align: left; width: 20%;"><?php echo $value['key']; ?></td>
                                    <td style="text-align: left;"><?php echo $value['value']; ?></td>
                                    <td style="text-align: left; width: 12%;"><?php echo $value['group']; ?></td>
                                    <td style="text-align: center; width: 15%;">
                                        <ul class="keywords">
                                            <li class="green-keyword">
                                                <a href="<?php echo admin_url() . 'setting/edit/' . $value['setting_id']; ?>"><?php trans('bt_edit'); ?></a>
                                            </li>
                                            <li class="red_fx_keyword">
                                                <a setting_id="<?php echo $value['setting_id']; ?>" href="javascript:;"><?php trans('bt_delete'); ?></a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</section>
<script>
    $(function(){
        $(".delete").click(function () {
            var $this=this
            if (confirm('Are you sure')) {
                var id=$($this).attr("setting_id");
                $.ajax({
                    type: "POST",
                    url: "<?php echo admin_url() ?>setting/delete",
                    data: "id="+id,
                    success: function(msg){
                        if(msg>=1){
                            $($this).parents('tr').fadeOut('slow');
                        }
                    }
                });
            }
        });
    });
</script>
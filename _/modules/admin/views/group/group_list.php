<section class="grid_12">
    <div class="block-border">
        <form class="block-content form" id="table_form" method="post" action="">
            <h1><?php trans($title); ?></h1>

            <div style="float: right; margin-right: 20px;" class="custom-btn">
                <button onclick="$(location).attr('href','<?php echo admin_url() . 'group/add' ?>');" style="float: left; height:30px;" type="button"><?php trans('add_new'); ?></button>
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
                                <?php trans('no.'); ?>
                            </th>
                            <th width="20%" scope="col" sType="string" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('name'); ?></th>
                            <th scope="col" sType="numeric" bSortable="true">
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                                <?php trans('description'); ?></th>
                            <th width="15%" scope="col" class="table-actions"><?php trans('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($list_group):
                            foreach ($list_group as $value):
                                ?>
                                <tr>
                                    <td style="text-align: left; width: 5%;"><?php echo $value['id']; ?></td>
                                    <td style="text-align: left; width: 20%;"><?php echo $value['name']; ?></td>
                                    <td style="text-align: left;"><?php echo $value['description']; ?></td>
                                    <td style="text-align: center; width: 15%;">
                                        <ul class="keywords">
                                            <li class="green-keyword">
                                                <a title="<?php trans('edit'); ?>" class="with-tip" href="<?php echo admin_url() . 'group/edit/' . $value['id']; ?>"><?php trans('edit'); ?></a>
                                            </li>
                                            <li class="red_fx_keyword">
                                                <a title="<?php trans('delete'); ?>"  class="with-tip delete" group_id="<?php echo $value['id']; ?>" href="javascript:;"><?php trans('delete'); ?></a>
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
                var id=$($this).attr("group_id");
                $.ajax({
                    type: "POST",
                    url: "<?php echo admin_url() ?>group/delete",
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
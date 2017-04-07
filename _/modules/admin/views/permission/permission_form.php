
<form name="formLanguage" class="form" id="complex_form" method="post" action="">
    <input type="hidden" name="checkpost" value="ok" />
    <section class="grid_12">
        <div class="block-border">
            <div class="block-content">
                <h1><?php trans('mn_permission'); ?></h1>
                <div class="columns">
                    <div class="block-controls">
                        <ul class="controls-buttons">
                            <li></li>
                        </ul>
                        <div style="float: right; margin-right: 20px;" class="custom-btn">
                            <button type="submit"><?php trans('bt_save'); ?></button>
                            <button onclick="$(location).attr('href','<?php echo admin_url() . 'permission' ?>');" type="button" class="red"><?php trans('bt_cancel'); ?></button>
                            <div style="clear: left;"></div>
                        </div>
                    </div>
                    <?php if (isset($error) && $error != '') : ?>
                        <ul class="message warning no-margin">
                            <li>
                                <?php
                                if (is_array($error)):
                                    foreach ($error as $value):
                                        echo '<p>' . $value . '</p>';
                                    endforeach;
                                else:
                                    echo $error;
                                endif;
                                ?>
                            </li>
                            <li class="close-bt"></li>
                        </ul>
                    <?php endif; ?>
                    <div class="columns">

                        <h2><?php trans('role'); ?> : <?php echo $role['0']['name']; ?></h2>

                        <div class="col200pxL-left">
                            <h3><?php trans('modules'); ?></h3>
                            <!-- Use the class js-tabs to enable JS tabs script -->
                            <ul class="side-tabs js-tabs same-height tab-permission">
                                <?php
                                if (is_array($list_resource) && count($list_resource) > 0) {
                                    $key_list_resource = array_keys($list_resource);
                                    foreach ($key_list_resource as $value) {
                                        echo '<li><a href="#tab-' . $value . '" title="' . $value . ' Permission">' . $value . trans('permission') . '</a></li>';
                                    }
                                }
                                ?>
                            </ul>

                        </div>
                        <div class="col200pxL-right">
                            <?php
                            if (is_array($list_resource) && count($list_resource) > 0) {
                                $i = 0;
                                foreach ($list_resource as $key => $value) {
                                    ?>
                                    <div id="tab-<?php echo $key; ?>" class="tabs-content" style="min-height: 300px; height: auto!important">
                                        <p>
                                            <input type="hidden" name="module[]" value="<?php echo $key; ?>"/>
                                            <input type="checkbox" value="checkall" <?php
                            if (isset($input->$key)) {
                                echo 'checked="checked"';
                            }
                                    ?> tab="tab-<?php echo $key; ?>" name="all-<?php echo $key; ?>" class="all-permission" id="all-<?php echo $key; ?>">
                                            <label for="all-<?php echo $key; ?>" class="inline"><strong><?php trans('all_permission'); ?></strong></label>
                                        </p>
                                        <?php
                                        if (is_array($value) == TRUE && count($value) > 0) {
                                            foreach ($value as $res) {
                                                ?>
                                                <div style="display:inline-block; width: 40%; margin-right: 20px;">
                                                    <input class="controller" type="checkbox" <?php
                                if (isset($input->$key->$res['controller'])) {
                                    echo 'checked="checked"';
                                }
                                                ?> name="<?php echo $key; ?>[<?php echo $res['controller'] ?>]" value="<?php echo $res['controller'] ?>" />
                                                    <label class="inline" style="font-weight: bold"><?php trans('controller'); ?> <?php echo $res['controller'] ?></label>
                                                    <div style="margin:10px 20px;">
                                                        <span class="label required"><?php trans('actions'); ?></span>
                                                        <p class="input-height grey-bg">
                                                            <?php
                                                            $action = explode('|', $res['action']);
                                                            if (is_array($action) == TRUE && count($action) > 0) {
                                                                foreach ($action as $ac) {
                                                                    ?>
                                                                    <input type="checkbox" <?php
                                            if (isset($input->$key->$res['controller']->$ac)) {
                                                echo 'checked="checked"';
                                            }
                                                                    ?> name="<?php echo $key; ?>[<?php echo $res['controller'] ?>][<?php echo $ac; ?>]" value="<?php echo $ac; ?>"/> <label><?php echo $ac; ?></label>
                                                                           <?php
                                                                       }
                                                                   }
                                                                   ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    $i = 1;
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
<script>
    $(document).ready(function(){
        $('.tabs-content').hide();
        $('.all-permission').change(function(){
            var $this=$(this);
            var $tab='#'+$this.attr('tab');
            if ($this.is(':checked'))
            {
                $($tab).find(':checkbox').attr('checked', this.checked);
            }else{
                $($tab).find(':checkbox').removeAttr('checked');
            }
        });
        $('.controller').change(function(){
            var $this=$(this);
            var $tab=$this.parent();
            if ($this.is(':checked'))
            {
                $($tab).find(':checkbox').attr('checked', this.checked);
            }else{
                $($tab).find(':checkbox').removeAttr('checked');
            }
        });
    });
</script>
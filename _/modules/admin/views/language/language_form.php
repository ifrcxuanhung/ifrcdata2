<form name="formLanguage" class="form" id="complex_form" method="post" action="">
    <section class="grid_12">
        <div class="block-border">
            <div class="block-content">
                <h1><?php trans('mn_language'); ?></h1>
                <div class="columns">
                    <div class="block-controls">
                        <ul class="controls-buttons">
                            <li></li>
                        </ul>
                        <div style="float: right; margin-right: 20px;" class="custom-btn">
                            <button type="submit"><?php trans('bt_save'); ?></button>
                            <button type="button" class="red" onclick="window.location='<?php echo admin_url(); ?>language'"><?php trans('bt_cancel'); ?></button>
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
                        <p class="colx3-left inline-label">
                            <span class="label required"><?php trans('name'); ?></span>
                            <span class="relative ">
                                <input type="text" name="name"  value="<?php echo $input['name']; ?>" class="full-width">
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label required"><?php trans('code'); ?></span>
                            <span class="relative ">
                                <input type="text" name="code"  value="<?php echo $input['code']; ?>" class="full-width">
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label required"><?php trans('sort_order'); ?></span>
                            <span class="relative ">
                                <input type="text" name="sort_order"  value="<?php echo $input['sort_order']; ?>" class="full-width">
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label required"><?php trans('status'); ?></span>
                            <span class="relative ">
                                <?php
                                $options = array(
                                    '1' => 'Enable',
                                    '0' => 'Disable',
                                );
                                echo form_dropdown('status', $options, $input['status'], 'class="full-width"');
                                ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
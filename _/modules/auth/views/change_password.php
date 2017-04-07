<h1>Change Password</h1>

<div id="infoMessage"><?php echo $message; ?></div>

<?php echo form_open("auth/change_password"); ?>

<p>Old Password:<br />
    <?php echo form_input($old_password); ?>
</p>

<p>New Password (at least <?php echo $min_password_length; ?> characters long):<br />
    <?php echo form_input($new_password); ?>
</p>

<p>Confirm New Password:<br />
    <?php echo form_input($new_password_confirm); ?>
</p>

<?php echo form_input($user_id); ?>
<p><?php echo form_submit('submit', 'Change'); ?></p>

<?php echo form_close(); ?>


<form name="formLanguage" class="form" id="complex_form" method="post" action="">
    <section class="grid_12">
        <div class="block-border">
            <div class="block-content">
                <h1><?php trans($title); ?></h1>
                <div class="columns">
                    <div class="block-controls">
                        <ul class="controls-buttons">
                            <li></li>
                        </ul>
                        <div style="float: right; margin-right: 20px;" class="custom-btn">
                            <button type="submit"><?php trans('save'); ?></button>
                            <button type="button" class="red"><?php trans('cancel'); ?></button>
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
                            <span class="label required">Name</span>
                            <span class="relative ">
                                <input type="text" name="name"  value="<?php echo $input['name']; ?>" class="full-width">
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label required">Code</span>
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
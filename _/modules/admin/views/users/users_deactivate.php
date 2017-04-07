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
                            <button onclick="$(location).attr('href','<?php echo admin_url() . 'users' ?>');" type="button" class="red"><?php trans('cancel'); ?></button>
                            <div style="clear: left;"></div>
                        </div>
                    </div>
                    <?php if (isset($message) && $message != '') : ?>
                        <ul class="message warning no-margin">
                            <li>
                                <?php echo $message; ?>
                            </li>
                            <li class="close-bt"></li>
                        </ul>
                    <?php endif; ?>
                    <?php echo form_hidden($csrf); ?>
                    <?php echo form_hidden(array('id' => $user->id)); ?>
                    <p><?php trans('Are you sure you want to deactivate the user'); ?> '<?php echo $user->username; ?>'</p>
                    <div class="colx3-left">
                        <p class="input-height grey-bg">
                            <input type="radio" name="confirm" value="yes" checked="checked" /><label for="field17-1"><?php trans('yes'); ?></label>
                            <input type="radio" name="confirm" value="no" /> <label for="field17-0"><?php trans('no') ?></label>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
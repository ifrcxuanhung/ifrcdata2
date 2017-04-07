
<form name="formLanguage" class="form" id="complex_form" method="post" action="">
    <section class="grid_12">
        <div class="block-border">
            <div class="block-content">
                <h1><?php trans('mn_Translate'); ?></h1>
                <div class="columns">
                    <div class="block-controls">
                        <ul class="controls-buttons">
                            <li></li>
                        </ul>
                        <div style="float: right; margin-right: 20px;" class="custom-btn">
                            <button type="submit"><?php trans('bt_save'); ?></button>
                            <button onclick="$(location).attr('href','<?php echo admin_url() . 'translate' ?>');" type="button" class="red"><?php trans('bt_cancel'); ?></button>
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
                        <label class="float-left margin-right width150"><?php trans('word') ?></label>
                        <textarea name="word" id="word" class="full-width"><?php echo isset($input['word']) ? $input['word'] : NULL; ?></textarea>
                    </div>
                    <div class="columns">
                        <div class="tabs-content">
                            <?php if (isset($list_language) && is_array($list_language)): ?>
                                <ul class="mini-tabs no-margin js-tabs same-height">
                                    <?php foreach ($list_language as $value) : ?>
                                        <li><a href="#tab-<?php echo $value['code'] ?>"><img src="<?php echo template_url() ?>images/icons/flags/<?php echo $value['code']; ?>.png" width="16" height="11" alt="<?php echo trans($value['name'], 1); ?>" title="<?php echo trans($value['name'], 1); ?>"> <?php echo trans($value['name'], 1); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php foreach ($list_language as $value) : ?>
                                    <div id="tab-<?php echo $value['code'] ?>" class="tabs-content-info">
                                        <label class="float-left margin-right width150"><?php trans('translate_max_250_characters') ?></label>
                                        <textarea name="translate[<?php echo $value['code'] ?>]" id="translate<?php echo $value['code'] ?>" class="full-width"><?php echo isset($input['translate'][$value['code']]) ? $input['translate'][$value['code']] : NULL; ?></textarea>
                                        <div class="clear height10"></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
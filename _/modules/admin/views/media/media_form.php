
<form name="formLanguage" class="form" id="complex_form" method="post" action="">
    <section class="grid_12">
        <div class="block-border">
            <div class="block-content">
                <h1><?php trans('mn_media'); ?></h1>
                <div class="columns">
                    <div class="block-controls">
                        <ul class="controls-buttons">
                            <li></li>
                        </ul>
                        <div style="float: right; margin-right: 20px;" class="custom-btn">
                            <button type="submit" name="ok" value="ok"><?php trans('bt_save'); ?></button>
                            <button onclick="$(location).attr('href','<?php echo admin_url() . 'media' ?>');" type="button" class="red"><?php trans('bt_cancel'); ?></button>
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
                            <span class="label"><?php trans('category') ?></span>
                            <span class="relative">
                                <?php
                                if (isset($input['category_id']) == FALSE) : $input['category_id'] = '';
                                endif;
                                if (isset($list_category) && is_array($list_category)):
                                    echo form_dropdown('category_id', $list_category, set_value('category_id', $input['category_id']), 'class =full-width');
                                endif;
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label"><?php trans('type'); ?></span>
                            <span class="relative">
                                <?php
                                if (isset($input['type']) == FALSE) : $input['type'] = '';
                                endif;
                                $type = array('image' => 'Image', 'video' => 'Video');
                                echo form_dropdown('type', $type, set_value('type', $input['type']), 'class =full-width');
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label"><?php trans('sort_order'); ?></span>
                            <span class="relative">
                                <?php
                                if (isset($input['sort_order']) == FALSE) : $input['sort_order'] = '';
                                endif;
                                echo form_input('sort_order', set_value('sort_order', $input['sort_order']));
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label"><?php trans('status'); ?></span>
                            <span class="relative ">
                                <?php
                                $options = array(
                                    '1' => 'Enable',
                                    '0' => 'Disable',
                                );
                                if (isset($input['status']) == FALSE) : $input['status'] = '';
                                endif;
                                echo form_dropdown('status', $options, set_value('status', $input['status']), 'class="full-width"');
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx2-left inline-label">
                            <span class="label"><?php trans('link'); ?></span>
                            <span class="relative">
                                <?php
                                if (isset($input['link']) == FALSE) : $input['link'] = '';
                                endif;
                                echo form_input('link', set_value('link', $input['link']), 'class="full-width"');
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label"><?php trans('image'); ?></span>
                        <div class="relative ">
                            <div class="image" style="display: block; clear: both; padding-left: 20em; ">
                                <img style="padding-bottom: 1em;" src="<?php
                                echo base_url();
                                echo (isset($input['thumb']) && $input['thumb'] != '') ? $input['thumb'] : 'assets/images/no-image.jpg';
                                ?>" alt="" id="thumb" />
                                <br />
                                <a class="pointer" onclick="image_upload('image', 'thumb');"><?php trans('browse_files'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#thumb').attr('src', '<?php echo base_url(); ?>assets/images/no-image.jpg'); $('#image').attr('value', '');"><?php trans('clear_image'); ?></a></div>
                            <?php
                            echo form_input('image', set_value('image', isset($input['image']) ? $input['image'] : ''), 'id="image" style="display:none"');
                            ?>
                        </div>
                        </p>
                    </div>
                    <div class="columns">
                        <div class="tabs-content" >
                            <?php if (isset($list_language) && is_array($list_language)): ?>
                                <ul class="mini-tabs no-margin js-tabs same-height">
                                    <?php foreach ($list_language as $value) : ?>
                                        <li><a href="#tab-<?php echo $value['code'] ?>"><img src="<?php echo template_url() ?>images/icons/flags/<?php echo $value['code']; ?>.png" width="16" height="11" alt="<?php echo trans($value['name'], 1); ?>" title="<?php echo trans($value['name'], 1); ?>"> <?php echo trans($value['name'], 1); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php foreach ($list_language as $value) : ?>
                                    <div id="tab-<?php echo $value['code'] ?>" class="tabs-content-info">
                                        <label class="float-left margin-right width150 required"><?php trans('title'); ?></label>
                                        <input type="text" value="<?php echo isset($input['title_' . $value['code']]) ? $input['title_' . $value['code']] : set_value('title_' . $value['code']); ?>" name="title_<?php echo $value['code'] ?>" id="title-<?php echo $value['code'] ?>" class="full-width">
                                        <div class="clear height10" style="margin-bottom: 20px"></div>
                                        <label class="float-left margin-right width150"><?php trans('description_max_250_characters'); ?></label>
                                        <textarea name="description_<?php echo $value['code'] ?>" id="description-<?php echo $value['code'] ?>" class="full-width"><?php echo isset($input['description_' . $value['code']]) ? $input['description_' . $value['code']] : set_value('description_' . $value['code']); ?></textarea>
                                        <div class="clear height10" style="margin-bottom: 20px"></div>
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
<script type="text/javascript">
    <!--
    function image_upload(field, thumb) {
        window.KCFinder = {};
        window.KCFinder.callBack = function(url) {
            $('#dialog').dialog('close');
            $url=url;
            $thumb=url.replace('assets/upload','assets/upload/thumbs');
            $('#image').val($url);
            $('#' + thumb).replaceWith('<img src="' + $thumb + '" alt="" id="' + thumb + '" />');
            window.KCFinder = null;
        };

        $('#dialog').remove();
        $('#complex_form').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="<?php echo base_url(); ?>assets/bundles/kcfinder/browse.php?type=images&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
        $('#dialog').dialog({
            title: 'Image Manager',
            close: function (event, ui) {

            },
            bgiframe: false,
            width: 800,
            height: 400,
            resizable: false,
            modal: false
        });
    };
    //-->
</script>
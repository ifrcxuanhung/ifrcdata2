
<form name="formLanguage" class="form" id="complex_form" method="post" action="">
    <section class="grid_12">
        <div class="block-border">
            <div class="block-content">
                <h1><?php trans('mn_category'); ?></h1>
                <div class="columns">
                    <div class="block-controls">
                        <ul class="controls-buttons">
                            <li></li>
                        </ul>
                        <div style="float: right; margin-right: 20px;" class="custom-btn">
                            <button type="submit"><?php trans('bt_save'); ?></button>
                            <button onclick="$(location).attr('href','<?php echo admin_url() . 'category' ?>');" type="button" class="red"><?php trans('bt_cancel'); ?></button>
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
                            <span class="label"><?php trans('parent'); ?></span>
                            <span class="relative">
                                <?php
                                if (isset($list_category) && is_array($list_category)):
                                    echo form_dropdown('parent_id', unshift($list_category, '0', 'none'), isset($input['parent_id']) ? $input['parent_id'] : NULL, 'class =full-width');
                                endif;
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label"><?php trans('code_category'); ?></span>
                            <span class="relative">
                                <?php
                                echo form_input('category_code', isset($input['category_code']) ? $input['category_code'] : NULL);
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label"><?php trans('sort_order'); ?></span>
                            <span class="relative">
                                <?php
                                echo form_input('sort_order', isset($input['sort_order']) ? $input['sort_order'] : NULL);
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
                                echo form_dropdown('status', $options, isset($input['status']) ? $input['status'] : NULL, 'class="full-width"');
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
                        <div class="tabs-content">
                            <?php if (isset($list_language) && is_array($list_language)): ?>
                                <ul class="mini-tabs no-margin js-tabs same-height">
                                    <?php foreach ($list_language as $value) : ?>
                                        <li><a href="#tab-<?php echo $value['code'] ?>"><img src="<?php echo template_url() ?>images/icons/flags/<?php echo $value['code']; ?>.png" width="16" height="11" alt="<?php echo trans($value['name'], 1); ?>" title="<?php echo trans($value['name'], 1); ?>"> <?php echo trans($value['name'], 1); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php foreach ($list_language as $value) : ?>
                                    <div id="tab-<?php echo $value['code'] ?>" class="tabs-content-info">
                                        <label class="float-left margin-right width150 required"><?php trans('name_category'); ?></label>
                                        <input type="text" value="<?php echo isset($input['name_' . $value['code']]) ? $input['name_' . $value['code']] : NULL; ?>" name="name_<?php echo $value['code'] ?>" id="name-<?php echo $value['code'] ?>" class="full-width">
                                        <div class="clear height10" style="margin-bottom: 20px"></div>

                                        <label class="float-left margin-right width150"><?php trans('description_max_250_characters'); ?></label>
                                        <textarea name="description_<?php echo $value['code'] ?>" id="description-<?php echo $value['code'] ?>" class="full-width"><?php echo isset($input['description_' . $value['code']]) ? $input['description_' . $value['code']] : NULL; ?></textarea>
                                        <div class="clear height10" style="margin-bottom: 20px"></div>

                                        <label class="float-left margin-right width150"><?php trans('meta_keywords_max_250_characters'); ?> </label>
                                        <textarea name="meta_keyword_<?php echo $value['code'] ?>" id="meta-keyword<?php echo $value['code'] ?>" class="full-width"><?php echo isset($input['meta_keyword_' . $value['code']]) ? $input['meta_keyword_' . $value['code']] : NULL; ?></textarea>
                                        <div class="clear height10" style="margin-bottom: 20px"></div>

                                        <label class="float-left margin-right width150"><?php trans('meta_description_max_250_characters'); ?></label>
                                        <textarea name="meta_description_<?php echo $value['code'] ?>" id="meta-description<?php echo $value['code'] ?>" class="full-width"><?php echo isset($input['meta_description_' . $value['code']]) ? $input['meta_description_' . $value['code']] : NULL; ?></textarea>
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
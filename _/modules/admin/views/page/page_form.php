
<form name="formLanguage" class="form" id="complex_form" method="post" action="">
    <section class="grid_12">
        <div class="block-border">
            <div class="block-content">
                <h1><?php trans('mn_page'); ?></h1>
                <div class="columns">
                    <div class="block-controls">
                        <ul class="controls-buttons">
                            <li></li>
                        </ul>
                        <div style="float: right; margin-right: 20px;" class="custom-btn">
                            <button type="submit"><?php trans('bt_save'); ?></button>
                            <button onclick="$(location).attr('href','<?php echo admin_url() . 'page' ?>');" type="button" class="red"><?php trans('bt_cancel'); ?></button>
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
                            <span class="relative">
                                <input type="text" value="<?php echo isset($input['name']) ? $input['name'] : NULL; ?>" name="name" id="name" class="full-width" />
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label required">Layout</span>
                            <span class="relative">
                                <input type="text" value="<?php echo isset($input['layout_id']) ? $input['layout_id'] : NULL; ?>" name="layout" id="layout" class="full-width" />
                            </span>
                        </p>
                    </div>
                    <div id="layout_id" class="columns hidden">
                        <p class="colx3-left inline-label">
                            <span class="label">&nbsp;</span>
                            <span class="relative">
                                <?php
                                    $options = array('Select layout', 'list.php', 'detail.php');
                                    echo form_dropdown('layout_id', $options, isset($input['layout_id']) ? $input['layout_id'] : NULL, 'class =full-width');
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label"><?php trans('sort'); ?></span>
                            <span class="relative">
                                <?php
                                    $options = array('desc' => 'Order Descending', 'asc' => 'Order Ascending');
                                    echo form_dropdown('sort_order', $options, isset($input['sort_order']) ? $input['sort_order'] : NULL, 'class =full-width');
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label required"><?php trans('category_article'); ?></span>
                            <span class="relative">
                                <input type="text" value="<?php echo isset($input['value']) ? $input['value'] : NULL; ?>" name="value" id="cate_art" class="full-width" />
                            </span>
                        </p>
                    </div>
                    <div id="category_id" class="columns hidden">
                        <p class="colx3-left inline-label">
                            <span class="label required"><?php trans('category'); ?></span>
                            <span class="relative">
                                <?php
                                if (isset($list_category) && is_array($list_category)):
                                    echo form_dropdown('category_id', unshift($list_category,'0','none'), isset($input['category_id']) ? $input['category_id'] : NULL, 'class =full-width');
                                endif;
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label">&nbsp;</span>
                            <span class="relative" id='rs'></span>
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
                                        <label class="float-left margin-right width150 required"><?php trans('title'); ?></label>
                                        <input type="text" value="<?php echo isset($input['title'][$value['code']]) ? $input['title'][$value['code']] : NULL; ?>" name="title[<?php echo $value['code'] ?>]" id="title-<?php echo $value['code'] ?>" class="full-width">
                                        <div class="clear height10" style="margin-bottom: 10px"></div>

                                        <label class="float-left margin-right width150"><?php trans('description'); ?></label>
                                        <div class="clear height10" style="margin-bottom: 10px"></div>

                                        <textarea name="description[<?php echo $value['code'] ?>]" id="description-<?php echo $value['code'] ?>" class="full-width"><?php echo isset($input['description'][$value['code']]) ? $input['description'][$value['code']] : NULL; ?></textarea>
                                        <div class="clear height10" style="margin-bottom: 10px"></div>

                                        <label class="float-left margin-right width150"><?php trans('meta_description'); ?></label>
                                        <input type="text" value="<?php echo isset($input['meta_description'][$value['code']]) ? $input['meta_description'][$value['code']] : NULL; ?>" name="meta_description[<?php echo $value['code'] ?>]" id="meta_description-<?php echo $value['code'] ?>" class="full-width">
                                        <div class="clear height10" style="margin-bottom: 10px"></div>

                                        <label class="float-left margin-right width150"><?php trans('meta_keyword'); ?></label>
                                        <input type="text" value="<?php echo isset($input['meta_keyword'][$value['code']]) ? $input['meta_keyword'][$value['code']] : NULL; ?>" name="meta_keyword[<?php echo $value['code'] ?>]" id="meta_keyword-<?php echo $value['code'] ?>" class="full-width">
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/bundles/ckeditor/ckeditor.js"></script>
<script>
$(document).ready(function(){
    $('select[name="category_id"]').live('change', function(){
        $('#rs').html('');
        var cate_id = $(this).val();
        $('#cate_art').val(cate_id);
        $.ajax({
            url: '<?php echo base_url(); ?>backend/page/listArticle',
            type: 'post',
            data: 'id=' + cate_id,
            async: false,
            success: function(rs){
                rs = JSON.parse(rs);
                var content = "<ul style='list-style-type: decimal; padding-left: 20px;'>";
                $(rs).each(function(i){
                    if(rs[i].lang_code == 'en'){
                        content += "<li><a href='#tin' class='cate' id=" + rs[i].article_id + ">" + rs[i].title + "</a></li>";
                    }
                });
                content += "</ul>";
                $('#rs').append(content);
            }
        });
        $('a.cate').click(function(){
            var art_id = $(this).attr('id');
            $('#cate_art').val(cate_id + ' | ' + art_id);
        });
    });

    $('select[name="layout_id"]').live('change', function(){
        $('#layout').val('');
        if($(this).val() != 0)
            $('#layout').val($('option[value='+$(this).val()+']').text());

    })

    showHidden('#layout', '#layout_id');
    showHidden('#cate_art', '#category_id');
});
<?php
if (isset($list_language) && is_array($list_language)):
    foreach ($list_language as $value) :
        ?>
    CKEDITOR.replace('description[<?php echo $value['code'] ?>]');
        <?php
    endforeach;
endif;
?>
</script>

<form name="formLanguage" class="form" id="complex_form" method="post" action="">
    <section class="grid_12">
        <div class="block-border">
            <div class="block-content">
                <h1><?php trans('mn_menu'); ?></h1>
                <div class="columns">
                    <div class="block-controls">
                        <ul class="controls-buttons">
                            <li></li>
                        </ul>
                        <div style="float: right; margin-right: 20px;" class="custom-btn">
                            <button type="submit"><?php trans('bt_save'); ?></button>
                            <button onclick="$(location).attr('href','<?php echo admin_url() . 'menu' ?>');" type="button" class="red"><?php trans('bt_cancel'); ?></button>
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
                            <span class="label"><?php trans('status'); ?></span>
                            <span class="relative float-left" style="padding-top:5px">
                                <input type="checkbox" <?php echo (isset($input['status']) && $input['status'] == 1) ? 'checked' : NULL; ?> name="status" id="status" />
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label"><?php trans('parent_menu'); ?></span><?php
                    echo "<!--";
                    print_r($list_menu);
                    echo "-->";
                    ?>
                            <span class="relative">
                                <?php
                                if (isset($list_menu) && is_array($list_menu)):
                                    //unset($list_menu[7]);
                                    echo form_dropdown('parent_id', unshift($list_menu, '0', 'Select menu'), isset($input['parent_id']) ? $input['parent_id'] : NULL, 'class =full-width');
                                endif;
                                ?>
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label"><?php trans('ordering'); ?></span>
                            <span class="relative">
                                <input type="text" value="<?php echo isset($input['sort_order']) ? $input['sort_order'] : NULL; ?>" name="sort_order" id="name" class="full-width" />
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label"><?php trans('type') ?></span>
                            <span class="relative">
                                <input type="radio" value="url" name="type" <?php if (isset($input['type'])) echo ($input['type'] == 'url' || !isset($input['type'])) ? 'checked' : NULL; ?> /> URL
                                <input type="radio" value="web" name="type" <?php if (isset($input['type'])) echo ($input['type'] == 'web') ? 'checked' : NULL; ?> /> <?php trans('web') ?>
                                <input type="radio" value="art" name="type" <?php if (isset($input['type'])) echo ($input['type'] == 'art') ? 'checked' : NULL; ?> /> <?php trans('articles'); ?>
                            </span>
                        </p>
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label required"><?php trans('url'); ?></span>
                            <span class="relative">
                                <input type="text" value="<?php echo isset($input['link']) ? $input['link'] : NULL; ?>" name="link" id="link" class="full-width" />
                            </span>
                        </p>
                    </div>
                    <div id="page_id" class="columns hidden">
                        <p class="colx3-left inline-label">
                            <span class="label required"><?php trans('web_page') ?></span>
                            <span class="relative">
                                <?php
                                if (isset($list_page) && is_array($list_page)):
                                    echo form_dropdown('page_id', unshift($list_page, '0', 'Select page'), isset($input['page_id']) ? $input['page_id'] : NULL, 'class =full-width');
                                endif;
                                ?>
                            </span>
                        </p>
                    </div>
                    <div id="category_id" class="columns hidden">
                        <p class="colx3-left inline-label">
                            <span class="label required"><?php trans('Category'); ?></span>
                            <span class="relative">
                                <?php
                                if (isset($list_category) && is_array($list_category)):
                                    echo form_dropdown('category_id', unshift($list_category, '0', 'Select category'), isset($input['category_id']) ? $input['category_id'] : NULL, 'class=full-width');
                                endif;
                                ?>
                            </span>
                        </p>
                    </div>
                    <div id='article_id' class="columns hidden">
                        <p class="colx3-left inline-label">
                            <span class="label">&nbsp;</span>
                            <span class="relative" id='rs'></span>
                        </p>
                        <input type='hidden' name='article_id' value='' />
                    </div>
                    <div class="columns">
                        <p class="colx3-left inline-label">
                            <span class="label"><?php trans('image'); ?></span>
                            <span class="relative">
                                <input type="text" value="<?php echo isset($input['image']) ? $input['image'] : NULL; ?>" name="image" id="image" class="full-width" />
                            </span>
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
                                        <label class="float-left margin-right width150"><?php trans('name_menu'); ?></label>
                                        <input type="text" value="<?php echo isset($input['name'][$value['code']]) ? $input['name'][$value['code']] : NULL; ?>" name="name[<?php echo $value['code'] ?>]" id="name-<?php echo $value['code'] ?>" class="full-width">
                                        <div class="clear height10" style="margin-bottom: 10px"></div>

                                        <label class="float-left margin-right width150"><?php trans('description'); ?></label>
                                        <div class="clear height10" style="margin-bottom: 10px"></div>

                                        <textarea name="description[<?php echo $value['code'] ?>]" id="description-<?php echo $value['code'] ?>" class="full-width"><?php echo isset($input['description'][$value['code']]) ? $input['description'][$value['code']] : NULL; ?></textarea>
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
    var url1;
    var url = $base_url;
    var url_temp = "<?php echo $input['link']; ?>";
    var cate_id = "<?php echo $input['category_id']; ?>";
    var art_id = "<?php echo $input['article_id']; ?>";
    var page_id = "<?php echo $input['page_id']; ?>";
    var type = "<?php echo $input['type']; ?>";
    if(type == 'art'){
        $('#page_id').slideUp();
        $('#category_id').slideDown();
        url = $base_url + 'article/';
        // show article
        $.ajax({
            url: '<?php echo base_url(); ?>backend/page/listArticle',
            type: 'post',
            data: 'id=' + cate_id,
            async: false,
            success: function(rs){
                if(rs != '[]'){
                    rs = JSON.parse(rs);
                    var content = "<ul style='list-style-type: decimal; padding-left: 20px;'>";
                    $(rs).each(function(i){
                        content += "<li><a href='#tin' class='cate' id='" + rs[i].article_id + "'>" + rs[i].title + "</a></li>";
                    });
                    content += "</ul>";
                    $('#rs').append(content);
                    $('#article_id').slideDown();
                    $('#' + art_id).css('color', 'red');
                }
            }
        });
    }else if(type == 'web'){
        $('#category_id').slideUp();
        $('#page_id').slideDown();
        url = $base_url + 'page/';
    }else{
        $('#category_id').slideUp();
        $('#page_id').slideUp();
    }
    $('input[name="type"]').change(function(){
        if($(this).prop('checked')){
            type = $(this).val();
            if(type == 'art'){
                $('#page_id').slideUp();
                $('#category_id').slideDown();
                url = $base_url + 'article/';
            }else if(type == 'web'){
                $('#category_id').slideUp();
                $('#page_id').slideDown();
                url = $base_url + 'page/';
            }else{
                $('#category_id').slideUp();
                $('#page_id').slideUp();
            }

        }
    });

    // bat dau su kien change select category
    $('select[name="page_id"]').live('change', function(){
        var id = $(this).val();
        var code = $('#page_id option[value=' + id + ']').text();
        code = code.toLowerCase();
        code = code.replaceAll(' ', '-');
        $('#link').val(url + code + '.html');
    });
    //ket thuc su kien change

    // bat dau su kien change select category
    $('select[name="category_id"]').live('change', function(){
        $('#rs').html('');
        $('#article_id').slideUp();
        cate_id = $(this).val();

        // thay doi textbox url
        $.ajax({
            url: $base_url + 'backend/menu/getCateCode',
            type: 'post',
            data: 'id=' + cate_id,
            async: false,
            success: function(code){
                if(code != ''){
                    url1 = url + code;
                    $('#link').val(url1 + '.html');
                }
            }
        });

        // show article
        $.ajax({
            url: '<?php echo base_url(); ?>backend/page/listArticle',
            type: 'post',
            data: 'id=' + cate_id,
            async: false,
            success: function(rs){
                if(rs != '[]'){
                    rs = JSON.parse(rs);
                    var content = "<ul style='list-style-type: decimal; padding-left: 20px;'>";
                    $(rs).each(function(i){
                        content += "<li><a href='#tin' class='cate' id='" + rs[i].article_id + "'>" + rs[i].title + "</a></li>";
                    });
                    content += "</ul>";
                    $('#rs').append(content);
                    $('#article_id').slideDown();
                }
            }
        });


    });

     // ket thuc su kien change
    //thay doi url textbox khi chon article
        $('a.cate').live('click', function(){
            var code = $(this).text();
            code = code.toLowerCase();
            code = code.replaceAll(' ', '-');
            if(url1 != null){
                $('#link').val(url1 + '/' + code + '.html');
            }else{
                code = code + '.html';
                url_arr = url_temp.split('/');
                var temp = url_temp.replaceAll(url_arr[6], code);
                url_temp = temp;
                $('#link').val(url_temp);
            }
            $('#article_id input[type="hidden"]').val($(this).attr('id'));
            $(this).css('color', 'red');
            $(this).parent().siblings().find('a.cate').attr('style', '');
        });
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
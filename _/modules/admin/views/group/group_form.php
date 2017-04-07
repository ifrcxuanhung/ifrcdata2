
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
                            <button onclick="$(location).attr('href','<?php echo admin_url() . 'group' ?>');" type="button" class="red"><?php trans('cancel'); ?></button>
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
                            <span class="label required"><?php trans('description'); ?></span>
                            <span class="relative ">
                                <textarea  name="description" class="full-width"><?php echo $input['description']; ?></textarea>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
<form name="formImport" class="form" id="complex_form" method="post" action=""  enctype="multipart/form-data" >
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
                            <button type="button" class="red" onclick="window.location='<?php echo admin_url(); ?>'"><?php trans('back'); ?></button>
                            <div style="clear: left;"></div>
                        </div>
                    </div>
                    <div class="columns">
                        <?php trans('done'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
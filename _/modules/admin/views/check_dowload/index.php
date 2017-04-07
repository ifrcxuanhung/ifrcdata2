<article class="fix_auto">
    <section class="grid_10">
        <div class="block-border">
            <div class="block-content">
                <h1><?php echo $title; ?></h1>
                <div class="block-controls">
                    <div class="custom-btn fx_bt_indi ">
                        <button type="button" class="" onclick="$('.form').submit();"><?php trans('Run'); ?></button>
                        <div style="clear: left;"></div>
                    </div>
                </div>
                <form action="" method="post" class="block-content form form_fx" enctype="multipart/form-data">
                    <ul class="blocks-list">
                        <li>
                            <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">URL</a>
                            <div class="columns">
                                <p class="colx2-right">
                                    <input type="text" name="url" id="complex-fr-subtitle" value="" class="full-width">
                                </p>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Post</a>
                            <div class="columns">
                                <p class="colx2-right">
                                    <input type="checkbox" name="post" value="1">

                                </p>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Param</a>
                            <div class="columns">
                                <p class="colx2-right">
                                    <input type="file" name="param">
                                </p>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Path Output</a>
                            <div class="columns">
                                <p class="colx2-right">
                                    <input type="text" name="path_output" id="complex-fr-subtitle" value="" class="full-width">
                                </p>
                            </div>
                        </li>

                    </ul>
                </form>
            </div>
        </div>
    </section>
</article>
<?php
if (isset($result)) {
    echo '<script>alert("' . $result . '");</script>';
}
?>
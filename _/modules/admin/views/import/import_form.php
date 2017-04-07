<form name="formImport" class="form" id="complex_form" method="post" action=""  enctype="multipart/form-data" >
    <section class="grid_12">
        <div class="block-border">
            <div class="block-content">
                <h1><?php trans('mn_import'); ?></h1>
                <div class="columns">
                    <div class="block-controls">
                        <ul class="controls-buttons">
                            <li></li>
                        </ul>
                        <div style="float: right; margin-right: 20px;" class="custom-btn">
                            <button type="button" class="action-import"><?php trans('bt_import'); ?></button>
                            <!--button type="button" class="red" onclick="window.location='<?php echo admin_url(); ?>'"><?php trans('bt_back'); ?></button-->
                            <div style="clear: left;"></div>
                        </div>
                    </div>
                    <div class="columns">
                        <input type="hidden" value="" class="folder-import"/>
                        <div class="heiht10 clear"></div>
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
                            <div id="uploader">

                            </div>

                            <table class="table table-action-inport" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" width="15%"><?php trans('file_upload'); ?></th>
                                        <th scope="col" width="15%"><?php trans('file_import'); ?></th>
                                        <th scope="col" width="15%"><?php trans('table'); ?></th>
                                        <th scope="col" width="10%"><?php trans('empty') ?></th>
                                        <th scope="col" width="10%"><?php trans('status') ?></th>
                                        <th scope="col" width="35%"><?php trans('import_result'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
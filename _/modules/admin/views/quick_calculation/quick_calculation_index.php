
<form name="formLanguage" class="form" id="complex_form" method="post" action="">
    <section class="grid_12">
        <div class="block-border">
            <div class="block-content">
                <h1><?php trans($title); ?></h1>
                <div class="columns">
                    <div class="block-controls">
                        <ul class="controls-buttons">
                            <li>
                                <button onclick="$(location).attr('href','<?php echo admin_url(); ?>');" type="button" class="red"><?php trans('back'); ?></button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="columns">
                    <ul class="tabs js-tabs same-height">
                        <li class="current"><a href="#tab-preparation"><?php trans('preparation') ?></a></li>
                    </ul>
                    <div class="tabs-content">
                        <div id="tab-preparation">
                            <ul class="mini-blocks-list">
                                <!-- Simple block -->
                                <li>
                                    <a href="javascript:void(0)" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16"> <?php trans('preparation') ?></a>
                                    <div class="check-contant-result"></div>
                                    <span class="empty float-right">
                                        <button class="small action-history-step" step="preparation" type="button"><?php trans('run') ?></button>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
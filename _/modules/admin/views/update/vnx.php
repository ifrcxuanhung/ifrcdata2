<style type="text/css">
.fix_auto_grid_7{
    margin: 2% 10% auto 30% !important;
    width: 70% !important;
}
</style>
<article class="fix_auto_grid_7">
    <section class="grid_7">
        <div class="block-border">
            <div class="block-content">
                <h1><?php trans('mn_choose_date'); ?></h1>
                <div class="block-controls">
                    <div class="custom-btn fx_bt_indi">
                        <button type="submit" class="run" action="vnx"><?php trans('bt_run'); ?></button>
                        <button type="button" class="red" onclick="$(location).attr('href','<?php echo admin_url(); ?>');"><?php trans('bt_close'); ?></button>
                    </div>
                </div>
                <form id="formCalculationDate" name="formCalculationDate" action="" method="post" class="block-content form form_fx">
                    <ul class="blocks-list">
                        <li>
                            <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16"><?php trans('date'); ?></a>
                            <div class="columns">
                                <p class="colx2-right">
                                    <input type="text" class="datepicker_fix" id="date" name="date" />
                                </p>
                            </div>
                        </li>
                    </ul>
                </form>
            </div></div>
    </section>
</article>
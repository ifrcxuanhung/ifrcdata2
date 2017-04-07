<div class="disable-form" style="display: none"><div class="my-progress"></div></div>
<article id="file-daily" class="fix_auto">
	<section class="grid_10">
        <div class="block-border">
        <div class="block-content">
            <h1>Report Month</h1>
            <div class="block-controls">
                <div class="custom-btn fx_bt_indi red">
                    <button type="button" class="" onclick="#');">Cancel</button>
                    <div style="clear: left;"></div>
                </div>
                <div class="custom-btn fx_bt_indi ">
                    <button type="button" id="save">Execute</button>
                <div style="clear: left;"></div>
                </div>
            </div>
            <form action="#" class="block-content form form_fx">
            <ul class="blocks-list">
                <li>
                    <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Month</a>
                    <div class="columns">
                        <p class="colx2-right">
                            <select name="month" id="month">
                            	<option value=0>Month</option>
                            	<?php
								for($i=1;$i<=12;$i++){
									echo '<option value='.$i.'>'.$i.'</option>';
								}
								?>
                            </select>
                            <select name="year" id="year">
                            	<option value='0'>Year</option>
                            	<?php
								for($j=(date('Y')-15);$j<=date('Y');$j++){
									echo '<option value='.$j.'>'.$j.'</option>';
								}
								?>
                            </select>
                            <img width="16" height="16" src="<?php echo base_url(); ?>assets/templates/backend/images/icons/fugue/calendar-month.png">
                        </p>
                     </div>
                </li>
            </ul>
            </form>
            
            
        </div></div>
    </section>

</article>
<div class="disable-form" style="display: none"><div class="my-progress"></div></div>
<article id="file-daily" class="fix_auto">
	<section class="grid_10">
        <div class="block-border">
        <div class="block-content">
            <h1>Daily From Jan 2013</h1>
            <div class="block-controls">
                <div class="custom-btn fx_bt_indi red">
                    <button type="button" class="" onclick="window.location.href='<?php echo admin_url(); ?>home'">Cancel</button>
                    <div style="clear: left;"></div>
                </div>
                <div class="custom-btn fx_bt_indi ">
                    <button type="button" id="save_2">Execute</button>
                <div style="clear: left;"></div>
                </div>
            </div>
            <form action="#" class="block-content form form_fx" id="form_daily_all">
                <ul class="blocks-list">
                    <li>
                        <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Start Date</a>
                        <div class="columns">
                            <p class="colx2-right">
                                <input type="text" name="startdate" id="startdate" value="" style="width: 60%"> <img width="16" height="16" src="<?php echo base_url(); ?>assets/templates/backend/images/icons/fugue/calendar-month.png">
                            </p>
                         </div>
                    </li>
                    <li>
                        <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">End Date</a>
                        <div class="columns">
                            <p class="colx2-right">
                                <input type="text" name="enddate" id="enddate" value="" style="width: 60%"> <img width="16" height="16" src="<?php echo base_url(); ?>assets/templates/backend/images/icons/fugue/calendar-month.png">
                            </p>
                         </div>
                    </li>
                    <li>
                        <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Process</a>
                        <div class="columns">
                            <p class="colx2-right">
                                <input type='radio' name='process' value='1'>From Exchange Files<br />
                                <input type='radio' name='process' value='0' checked='checked' style='margin-top:10px'>From Final REF, UPC
                            </p>
                        </div>
                    </li>
                     <li>
                        <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Replace</a>
                        <div class="columns">
                            <p class="colx2-right">
                                <input type='radio' name='check' value='1'>Agree&nbsp;&nbsp;<input type='radio' name='check' value='0' checked='checked'>Don't Agree
                            </p>
                        </div>
                    </li>
                </ul>
            </form>
            
            
        </div></div>
    </section>

</article>
<script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  </script>
<div class="disable-form">
    <article class="fix_auto">
	<section class="grid_10">
        <div class="block-border">
        <div class="block-content">
            <h1>Query</h1>
            <div class="block-controls">
                <div class="custom-btn fx_bt_indi red">
                    <button type="button" class="" onclick="window.location.href='<?php echo admin_url(); ?>home'">Cancel</button>
                    <div style="clear: left;"></div>
                </div>
                <div class="custom-btn fx_bt_indi ">
                    <button type="button" class="submit">Excute</button>
                <div style="clear: left;"></div>
                </div>
            </div>
            <form id="profile" name="profile" action="" method="post" class="block-content form form_fx">
            <ul class="blocks-list">
            
                <li>
                    <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Source</a>
                    <p class="colx2-right">
                        <select name="source" id="source" class="full-width">
                            <option value="0" selected>ALL</option>
                    <?php
                        foreach($sources as $value){
                            ?>
                            <option value="<?php echo $value['source']; ?>"><?php echo $value['source']; ?></option>
                            <?php
                        }
                    ?>
                        </select>
                    </p>
                </li>
                <li>
                    <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Information</a>
                    <p class="colx2-right">
                        <select name="information" id="information" class="full-width">
                            <option value="0" selected>ALL</option>
                    <?php
                        foreach($infos as $value){
                            ?>
                            <option value="<?php echo $value['information']; ?>"><?php echo $value['information']; ?></option>
                            <?php
                        }
                    ?>
                        </select>
                    </p>
                </li>
                <li>
                    <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Market</a>
                    <p class="colx2-right">
                        <select name="market" id="market" class="full-width">
                            <option value="0" selected>ALL</option>
                <?php
                    foreach($markets as $value){
                        if($value['market'] != 'ALL'){
                            ?>
                            <option value="<?php echo $value['market']; ?>"><?php echo $value['market']; ?></option>
                            <?php
                        }
                    }
                ?>
                        </select>
                    </p>
                </li>
                <li>
                    <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Ticker</a>
                    <p class="colx2-right">
                        <select name="code" id="ticker" class="full-width">
                            <option value="0" selected>ALL</option>
                    <?php
                        foreach($tickers as $value){
                            ?>
                            <option value="<?php echo $value['code']; ?>"><?php echo $value['code']; ?></option>
                            <?php
                        }
                    ?>
                        </select>
                    </p>
                </li>
                
            </ul>
            </form>
            
        </div></div>
    </section>

</article>
</div>
<script>
$(function(){
    $(".submit").click(function(){
        var src = $("#source").val();
        var info = $("#information").val();
        var market = $("#market").val();
        var code = $("#ticker").val();
        //$html = "<img src='" + $base_url + "assets/templates/backend/images/mask-loader.gif' style='position: absolute; top: 50%; left: 50%;' />";
        $(".disable-form").append("<img src='" + $base_url + "assets/templates/backend/images/mask-loader.gif' style='position: absolute; top: 50%; left: 50%;' />");
        $(".fix_auto").hide();
        $.ajax({
            url: $admin_url + 'profile/index',
            type: 'post',
            data: 'source=' + src + '&information=' + info + '&market=' + market + '&code=' + code,
            async: false,
            success: function(){
                document.location.href = $admin_url + 'profile/result';
            }
        });
    });
});
</script>
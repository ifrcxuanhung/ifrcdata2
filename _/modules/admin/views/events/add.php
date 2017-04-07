<style>
.block-controls{
    margin-bottom: 6px;
}
.blocks-list li{
    padding-top: 5px;
    padding-bottom: 5px;
}
textarea{
    font-size: 12px;
}
.message.error{
    clear: both;
    float: right;
    margin-top: 5px;
    width: 39%;
}
input[type="submit"], input[type="reset"]{
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background: -moz-linear-gradient(center top , white, #72C6E4 4%, #0C5FA5) repeat scroll 0 0 transparent;
    border-color: #50A3C8 #297CB4 #083F6F;
    border-image: none;
    border-radius: 0.333em 0.333em 0.333em 0.333em;
    border-style: solid;
    border-width: 1px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
    color: white;
    cursor: pointer;
    display: inline-block;
    font-size: 1.167em;
    font-weight: bold;
    line-height: 1.429em;
    padding: 0.286em 1em 0.357em;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
}

.block-controls-2 {
    background: -moz-linear-gradient(center bottom , white, #E5E5E5 88%, #D8D8D8) repeat scroll 0 0 transparent;
    border-top: 1px solid #999999;
    margin: 10px -1.667em 0 -1.667em;
    padding: 1em;
    text-align: right;
}

.fx_bt_indi_2{
    float: left;
    margin-right: 20px;
    margin-top: -5px;
    position: relative;
    z-index: 200;
}
</style>
<article class="no-margin">
    <form action="" method="post" class="form">
        <section id="box-info" style="width:65% !important" class="grid_5"<?php if(isset($input)) echo ' style="display: none;"'; ?>>
            <div class="block-border">
                <div class="block-content">
                <h1>Events</h1>
                    <div class="block-controls">
                        <div class="fx_bt_indi">
                            <span style="font-weight:bold">Ticker:</span> <?php echo isset($info['ticker']) ? $info['ticker'] : NULL; ?> | <span style="font-weight:bold">Market:</span> <?php echo isset($info['market']) ? $info['market'] : NULL; ?> | <span style="font-weight:bold">Date Ann:</span> <?php echo isset($info['date_ann']) ? $info['date_ann'] : NULL; ?>
                            <div style="clear: left;"></div>
                        </div>
                    </div>
                    <?php 
                        if(isset($info['content'])){
                            $info['content'] = str_replace('&lt;br/&gt;', "&#10;", $info['content']);
                            $info['content'] = strip_tags(html_entity_decode(htmlspecialchars_decode($info['content'])));
                            if(strpos($info['content'],'.pdf') || strpos($info['content'],'.docx')){
                                echo '<textarea name="dividends-text" id="dividends-text" style="height:320px; width: 98%" disabled>'.$info['content'].'</textarea>';
                                $data_split = trim(preg_replace('/(.*)File đính kèm:/', '', $info['content']));
                                $date = str_replace('-','/',$info['date_ann']);
                    ?>
                                <div class="block-controls-2">
                                    <div class="fx_bt_indi" style="padding-top: 8px;">
                                        <span style="font-weight:bold">Attachments:</span> 
                                        <?php
                                            if($info['market'] == 'HSX'){
                                                echo '<a href="http://www.hsx.vn/hsx/Uploaded/'.$date.'/'.$data_split.'" target="_blank">'.$data_split.'</a>';
                                            }elseif($info['market'] == 'HNX'){
                                                echo '<a href="http://www.hnx.vn/web/guest/tin-niem-yet?p_p_id=newnyuc_WAR_HnxIndexportlet&p_p_lifecycle=1&p_p_state=exclusive&p_p_mode=view&_newnyuc_WAR_HnxIndexportlet_anchor=viewAction&_newnyuc_WAR_HnxIndexportlet_cmd=dlAction&_newnyuc_WAR_HnxIndexportlet_dl_link='.$data_split.'" target="_blank">'.$data_split.'</a>';
                                            }
                                        ?>
                                        <div style="clear: left;"></div>
                                    </div>
                                </div>
                    <?php
                            }else{
                                echo '<textarea name="dividends-text" id="dividends-text" style="height:355px; width: 98%" disabled>'.$info['content'].'</textarea>';
                            }
                        }else{
                            echo '<textarea name="dividends-text" id="dividends-text" style="height:320px; width: 98%" disabled></textarea>';
                        }
                    ?>
                </div>
            </div>
        </section>
        <section id="box-detail" style="width:30% !important" class="grid_5"<?php if(isset($input)) echo ' style="margin-left: 30%"'; ?>>
            <div class="block-border" style="height:440px;">
                <div class="block-content" style="height:370px;">
                    <h1><?php trans('mn_type'); ?></h1>
                    <div class="block-controls">
                    </div>
                    <?php if (isset($error) && $error != '') : ?>
                        <ul class="message error no-margin">
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
                        </ul>
                    <?php endif; ?>
                        <ul class="blocks-list">
                            <input type="hidden" name="ticker" value="<?= $info['ticker'] ?>" />
                            <input type="hidden" name="market" value="<?= $info['market'] ?>" />
                            <input type="hidden" name="date_ann" value="<?= $info['date_ann'] ?>" />
                            <?php
                                foreach($info_type as $it){
                                    if(in_array($it['id'],$info_check)){
                            ?>
                                        <li>
                                            <div class="columns" style="top:10px">
                                                <p style="float:left; width:50%;">
                                                    <img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16"><?= $it['evname_en'] ?>
                                                </p>
                                                <p class="colx2-right" style="width:10% !important">
                                                    <input type="checkbox" name="type[]" value="<?= $it['id'] ?>" checked>
                                                </p>
                                            </div>
                                        </li>
                            <?php
                                    }else{
                            ?>
                                        <li>
                                            <div class="columns" style="top:10px">
                                                <p style="float:left; width:50%;">
                                                    <img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16"><?= $it['evname_en'] ?>
                                                </p>
                                                <p class="colx2-right" style="width:10% !important">
                                                    <input type="checkbox" name="type[]" value="<?= $it['id'] ?>">
                                                </p>
                                            </div>
                                        </li>
                            <?php
                                    }
                                }
                            ?>
                        </ul>
                        <div style="position:absolute; bottom:10px; right:20px">
                            <input type="reset" id="btn-back" name="back" value="Back" />
                            <input type="submit" name="ok" value="Classified" />
                            <input type="reset" id="btn-cancel" value="Cancel" />
                        </div>
                    </div>
                </div>
        </section>
    </form>
</article>

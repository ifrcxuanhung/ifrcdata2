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
                    <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Code DWL</a>
                    <p class="colx2-right">
                        <select name="code_dwl" id="code_dwl" class="full-width">
                            <option value="0" selected>ALL</option>
                      <?php
                        if(is_array($info['code_dwl'])){
                          foreach($info['code_dwl'] as $item){
                            ?>
                            <option value="<?php echo $item['code_dwl']; ?>"><?php echo $item['code_dwl']; ?></option>
                            <?php
                          }
                        }
                      ?>
                        </select>
                    </p>
                </li>
                <li>
                    <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Code Info</a>
                    <p class="colx2-right">
                        <select name="code_info" id="code_info" class="full-width">
                            <option value="0" selected>ALL</option>
                      <?php
                        if(is_array($info['code_info'])){
                          foreach($info['code_info'] as $item){
                            ?>
                            <option value="<?php echo $item['code_info']; ?>"><?php echo $item['code_info']; ?></option>
                            <?php
                          }
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
                    if(is_array($info['market'])){
                      foreach($info['market'] as $item){
                        if($item['market'] != 'ALL'){
                            ?>
                            <option value="<?php echo $item['market']; ?>"><?php echo $item['market']; ?></option>
                            <?php
                        }
                      }
                    }
                  ?>
                        </select>
                    </p>
                </li>
                <li>
                    <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">URL</a>
                    <p class="colx2-right">
                        <select name="url" id="url" class="full-width">
                            <option value="0" selected>ALL</option>
                      <?php
                        if(is_array($info['url'])){
                          foreach($info['url'] as $item){
                            ?>
                            <option value="<?php echo $item['url']; ?>"><?php echo $item['url']; ?></option>
                            <?php
                          }
                        }
                      ?>
                        </select>
                    </p>
                </li>
                <li>
                    <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Input</a>
                    <p class="colx2-right">
                        <select name="input" id="input" class="full-width">
                            <option value="0" selected>ALL</option>
                  <?php
                    if(is_array($info['input'])){
                      foreach($info['input'] as $item){
                        if($item['input'] != ''){
                            ?>
                            <option value="<?php echo $item['input']; ?>"><?php echo $item['input']; ?></option>
                            <?php
                        }
                      }
                    }
                  ?>
                        </select>
                    </p>
                </li>
                <li>
                    <a href="#" class="float-left"><img src="<?php echo template_url(); ?>images/icons/fugue/status.png" width="16" height="16">Time</a>
                    <p class="colx2-right">
                        <select name="code" id="ticker" class="full-width">
                            <option value="0" selected>ALL</option>
                  <?php
                    if(is_array($info['time'])){
                      foreach($info['time'] as $item){
                        if($item['time'] != ''){
                            ?>
                            <option value="<?php echo $item['time']; ?>"><?php echo $item['time']; ?></option>
                            <?php
                        }
                      }
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
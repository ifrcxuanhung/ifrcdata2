<div class="title_stk"><h1><?php echo $ref[0]['idx_name_sn2']; ?></h1></div>
<article class="">
  <section class="grid_12">
    <div class="block-border">
      <form class="block-content form" id="table_form" method="post" action="">
        <h1>Idx_specs</h1>
        <div class="no-margin"> 
          
          <table class="table sortable no-margin" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th scope="col" sType="numeric" bSortable="true" style="text-align: left; width: 10%;"> 
                <?php echo trans('last'); ?>
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th width="15%" scope="col" sType="numeric" bSortable="true" style="text-align: left;"> 
                <?php echo trans('%var'); ?>
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th width="10%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                <?php echo trans('pclose'); ?>
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                <th scope="col" sType="numeric" bSortable="true" style="text-align: left; width: 10%;"> 
                <?php echo trans('change'); ?>
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th width="10%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                <?php echo trans('%dvar'); ?>
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                <?php echo trans('time'); ?>
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>                
              </tr>
            </thead>
            <tbody>
          <?php
            if(is_array($specs)){
              foreach($specs as $item){
                $change = $item['idx_last'] - $item['idx_pclose'];
                ?>
              <tr>
                <td width="10%" style="text-align: left; vertical-align: middle;"><strong><?php echo number_format($item['idx_last'], 2); ?></strong></td>
                <td width="30%" style="text-align: left; vertical-align: middle; <?php echo highlight_number($item['idx_var']); ?>"><?php echo number_format($item['idx_var'] * 100); ?></td>
                <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo number_format($item['idx_pclose']); ?></td>
                <td width="10%" style="text-align: left; vertical-align: middle; <?php echo highlight_number($change); ?>"><?php echo number_format($change, 2); ?></td>
                <td width="10%" style="text-align: right; vertical-align: middle; <?php echo highlight_number($item['idx_dvar']); ?>"><?php echo number_format($item['idx_dvar'] * 100); ?></td>
                <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo $item['times']; ?></td>                
              </tr>
              <?php
              }
            }
          ?>

            </tbody>
          </table>
        </div>
      </form>
    </div>
  </section>
  <section class="grid_5">
    <div class="block-border">
      <form class="block-content form" id="table_form" method="post" action="">
        <h1>Idx_ref</h1>
        
        <div class="no-margin"> 
          <table class="table sortable no-margin" cellspacing="0" width="100%" height="100">
            <thead>
              <tr>
                <th width="20%" scope="col" sType="string" bSortable="true">idx_ref<span class="column-sort"> <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> </span></th>
                <th width="20%" scope="col" sType="string" bSortable="true">Detail<span class="column-sort"> <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> </span> </th>
                
              </tr>
            </thead>
            <tbody>
            <?php if(is_array($ref)){ ?>
              <tr>
                <td style="text-align: left; width: 5%;">CODE</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['idx_code']; ?></td>
                
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">CURRENCY</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['idx_curr']; ?></td>
                
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">BASE</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['idx_base'];  ?></td>
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">DATE BASE</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['idx_dtbase']; ?></td>
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">TYPE</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['idx_type']; ?></td>
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">MOTHER</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['idx_mother'] . ' (' . $ref[0]['idx_name_sn'] . ')'; ?></td>
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">CATEGORY</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['idx_bbs']; ?></td>
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">CONSTITUENTS</td>
                <td style="text-align: left; width: 20%;"><?php echo $const; ?></td>
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">LINKED INDEXES</td>
                <td style="text-align: left; width: 20%;">
                  <span><?php echo $linked; ?></span>
                  <ul code='<?php echo $ref[0]['idx_code']; ?>' class="keywords" style='cursor: pointer; float: right;'>
                    <li>Info</li>
                  </ul>
                </td>
              </tr>
            <?php
              }
            ?>
            </tbody>
          </table>
        </div>
      </form>
    </div>
  </section>
  <section class="grid_7">
    <div class="block-border">
      <form class="block-content form" id="table_form" method="post" action="">
        <h1>Idx_ca</h1>
        <div class="no-margin"> 
          
          <table class="table sortable no-margin" cellspacing="0" width="100%" height='200'>
            <thead>
              <tr>
                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: left;"> 
                  stk_code
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th width="15%" scope="col" sType="numeric" bSortable="true" style="text-align: right;"> 
                	Date
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th width="15%" scope="col" sType="formatted-num" bSortable="true" style="text-align: right;">
                	Shares
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                Float 
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                <th width="5%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                Capp 
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                <th width="25%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                Adj Close 
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                <th width="10%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                Intro 
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                
                
              </tr>
            </thead>
            <tbody>
            <?php 
              if(is_array($ca)){
                foreach($ca as $item){
              ?>
              <tr>
                <td style="text-align: left;"><?php echo $item['stk_code']; ?></td>
                <td style="text-align: right;"><?php echo $item['dates']; ?></td>
                <td style="text-align: right;"><?php echo number_format($item['new_shares']); ?></td>
                <td style="text-align: right;"><?php echo number_format($item['nxt_free_float']); ?></td>
                <td style="text-align: right;"><?php echo number_format($item['nxt_capping']); ?></td>
                <td style="text-align: right;"><?php echo number_format($item['adj_close']); ?></td>
                <td style="text-align: right;"><?php echo number_format($item['intro']); ?></td>
              </tr>
              <?php
                }
              }
            ?>
            </tbody>
          </table>
        </div>
      </form>
    </div>
  </section>
  <section class="grid_12">
    <div class="block-border">
      <form class="block-content form" id="table_form" method="post" action="">
        <h1>Idx_composition</h1>
        <div class="no-margin"> 
          
          <table class="table sortable no-margin" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th scope="col" sType="string" bSortable="true" style="text-align: left; width: 10%;"> 
                stk-code
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th scope="col" sType="string" bSortable="true" style="text-align: left; width: 10%;"> 
                idx-code
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: left;"> 
                stk-name
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th width="10%" scope="col" sType="formatted-num" bSortable="true" style="text-align: right;">
                Shares
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                <th width="10%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                Market cap
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                Capp
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                <th width="25%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                Float
                <span class="column-sort"> 
                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                </span> 
                </th>
                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                WGT
                    <span class="column-sort" > 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                
                
              </tr>
            </thead>
            <tbody>
          <?php
            if(is_array($composition)){
              foreach($composition as $item){
                ?>
              <tr>
                <td width="10%" style="text-align: left; vertical-align: middle;"><a href="<?php echo admin_url() . 'stk_page/index/' . $item['stk_code']; ?>"><?php echo $item['stk_code']; ?></a></td>
                <td width="10%" style="text-align: left; vertical-align: middle;"><?php echo $item['idx_code']; ?></td>
                <td width="30%" style="text-align: left; vertical-align: middle;"><?php echo $item['stk_name']; ?></td>
                <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo $item['stk_shares_idx']; //number_format($item['stk_shares_idx']); ?></td>
                <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo number_format($item['stk_mcap_idx']); ?></td>
                <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo number_format($item['stk_capp_idx'], $decimal['stk_capp_idx']); ?></td>
                <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo number_format($item['stk_float_idx']); ?></td>
                <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo number_format($item['stk_wgt'], 1); ?>0</td>
              </tr>
              <?php
              }
            }
          ?>

            </tbody>
          </table>
        </div>
      </form>
    </div>
  </section>
  <div class="clear"></div>
</article>
<script>
$(document).live("ready", function(){
  $(".block-footer .float-left").remove();
})
</script>
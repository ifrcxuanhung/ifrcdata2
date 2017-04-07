<div class="title_stk"><h1><?php echo $ref[0]['stk_name']; ?></h1></div>
<article class="">
  <section class="grid_5">
    <div class="block-border">
      <form class="block-content form" id="table_form" method="post" action="">
        <h1>Stk_ref</h1>
        
        <div class="no-margin"> 
          <table class="table sortable no-margin" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th width="20%" scope="col" sType="numeric" bSortable="true">stk_ref<span class="column-sort"> <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> </span></th>
                <th width="20%" scope="col" sType="string" bSortable="true">Detail<span class="column-sort"> <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> </span> </th>
                
              </tr>
            </thead>
            <tbody>
            <?php if(is_array($ref)){ ?>
              <tr>
                <td style="text-align: left; width: 5%;">IPO</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['ipo']; ?></td>
                
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">CODE</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['stk_code']; ?></td>
                
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">MARKET</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['stk_market'] . ' (' . $ref[0]['mar_name'] . ')'; ?></td>
                
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">SECTOR</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['stk_sector'] . ' (' . $ref[0]['sec_name'] . ')';  ?></td>
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">CURR</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['stk_curr']; ?></td>
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">MULT</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['stk_mult']; ?></td>
              </tr>
              <tr>
                <td style="text-align: left; width: 5%;">TICKER</td>
                <td style="text-align: left; width: 20%;"><?php echo $ref[0]['ticker']; ?></td>
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
          
          <table class="table sortable no-margin" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th width="15%" scope="col" sType="numeric" bSortable="true" style="text-align: left;"> 
                  idx_code
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
                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: right;">
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
                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: right;">
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
                <th width="15%" scope="col" sType="string" bSortable="true" style="text-align: right;">
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
                <td style="text-align: left;"><?php echo $item['idx_code']; ?></td>
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
                <th scope="col" sType="numeric" bSortable="true" style="text-align: left; width: 10%;"> 
                idx-code
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th scope="col" sType="numeric" bSortable="true" style="text-align: left; width: 10%;"> 
                stk-code
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th width="15%" scope="col" sType="numeric" bSortable="true" style="text-align: left;"> 
                stk-name
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th width="10%" scope="col" sType="string" bSortable="true" style="text-align: right;">
                Shares
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                <th width="10%" scope="col" sType="formatted-num" bSortable="true" style="text-align: right;">
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
                <th width="15%" scope="col" sType="numeric" bSortable="true" style="text-align: right;">
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
                <td width="10%" style="text-align: left; vertical-align: middle;"><a href="<?php echo admin_url() . 'idx_page/index/' . $item['idx_code']; ?>"><?php echo $item['idx_code']; ?></a></td>
                <td width="10%" style="text-align: left; vertical-align: middle;"><?php echo $item['stk_code']; ?></td>
                <td width="30%" style="text-align: left; vertical-align: middle;"><?php echo $item['stk_name']; ?></td>
                <td width="10%" style="text-align: right; vertical-align: middle;"><?php echo number_format($item['stk_shares_idx'], 0); ?></td>
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
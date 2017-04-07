<article class="">
  <section class="grid_12">
    <div class="block-border">
      <form class="block-content form" id="table_form" method="post" action="">
        <h1>Results</h1>
        <div class="no-margin"> 
          
          <table class="table sortable no-margin" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th scope="col" sType="numeric" bSortable="true" style="text-align: left; width: 10%;"> 
                <?php echo trans('Source'); ?>
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th width="15%" scope="col" sType="numeric" bSortable="true" style="text-align: left;"> 
                <?php echo trans('Information'); ?>
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>
                <th width="10%" scope="col" sType="string" bSortable="true" style="text-align: left;">
                <?php echo trans('Market'); ?>
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span> 
                </th>
                <th scope="col" sType="numeric" bSortable="true" style="text-align: right;"> 
                <?php echo trans('Value'); ?>
                    <span class="column-sort"> 
                        <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a> 
                        <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a> 
                    </span>
                </th>           
              </tr>
            </thead>
            <tbody>
          <?php
            if(is_array($results)){
              foreach($results as $item){
                ?>
              <tr>
                <td width="30%" style="text-align: left; vertical-align: middle;"><?php echo $item['source']; ?></td>
                <td width="30%" style="text-align: left; vertical-align: middle;"><?php echo $item['information']; ?></td>
                <td width="20%" style="text-align: left; vertical-align: middle;"><?php echo ($item['market'] == 0) ? 'ALL' : $item['market']; ?></td>
                <td width="20%" style="text-align: right; vertical-align: middle;"><?php echo $item['values']; ?></td>                
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
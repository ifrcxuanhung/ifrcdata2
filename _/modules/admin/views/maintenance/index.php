<style type="text/css">
.block-controls-2 {
    background: -moz-linear-gradient(center bottom , white, #E5E5E5 88%, #D8D8D8) repeat scroll 0 0 transparent;
    border-top: 1px solid #999999;
    margin: 10px -1.667em 0 -1.667em;
    padding: 1em;
    text-align: right;
}
h2.fx_pd_tle{
    padding: 1em !important;
    float: left;
    width:10%;
}
.fx_bt_indi_2{
    float: left;
    margin-right: 20px;
    margin-top: -5px;
    position: relative;
    z-index: 200;
}

#submit{
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
thead tr{
    height:45px;
}
.block-content .no-margin{
    margin: 0px 0 0 0 !important;
    overflow-x:auto;
    overflow-y:scroll; 
    min-height:auto;
    max-height: 340px;
}
.block-content .message{
    margin: -20px 0 0 0 !important;
}
.numeric{
    text-align: right !important;
}
</style>
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" action="" method="post">
            <h1><?php trans('mn_maintenance'); ?></h1>
            <div class="block-content fx_pdd">
                <!--h1>Database</h1-->
                <div class="block-controls">
                    <div class="infos fx_pd_bot">
                        <!--<h2 class="fx_pd_tle"><?php trans('title_maintenance') ?></h2>-->
                        <?php 
                            if(isset($_GET['date'])){
                                $filter = $_GET['date'];
                            }else{
                                $filter = '';
                            } 
                        ?>
                        Date: <input type="text" id="date" value="<?= $filter ?>"/>
                        <select name="select" id="select">
                            <?php
                                $data_type = array('all','pvn');
                                foreach($data_type as $type){
                                    if(isset($_GET['type'])){
                                        if($type == $_GET['type']){
                                            pre($type." ".$_GET['type']);
                                            echo '<option value="'.$type.'" selected>'.strtoupper($type).'</option>';
                                        }else{
                                            echo '<option value="'.$type.'">'.strtoupper($type).'</option>';
                                        }
                                    }else{
                                        echo '<option value="'.$type.'">'.strtoupper($type).'</option>';
                                    }
                                }
                            ?>
                        </select>
                        <button type="button" class="blue" id="submit">Submit</button>
                    </div>
                </div>
                <p class="grey"> </p>
                <dl class="accordion">
                    <dt><span class="number">+</span>From Reference</span><span style="float:right">Number: <?= $number_changes ?></span></dt>
                    <dd>
                        <table class="table sortable table-ajax" footer="false" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="7%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('date'); ?>
                                    </th>
                                    <th width="7%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('pdate'); ?>
                                    </th>
                                    <th width="12%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('ticker') ?>
                                    </th>
                                    <th width="12%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('pticker') ?>
                                    </th>
                                    <th width="12%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('market') ?>
                                    </th>
                                    <th width="11%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('pmarket') ?>
                                    </th>
                                    <th width="11%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('shli') ?>
                                    </th>
                                    <th width="11%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('pshli') ?>
                                    </th>
                                    <th width="10%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('shou') ?>
                                    </th>
                                    <th width="15%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('pshou') ?>
                                    </th>
                                    <th width="15%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('shou_add') ?>
                                    </th>
                                    <th width="15%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('shli_add') ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($changes as $cha){
                                        if($cha['shli'] != 0){
                                            $cha['shli'] = number_format($cha['shli']);
                                        }else{
                                            $cha['shli'] = '';
                                        }
                                        if($cha['pshli'] != 0){
                                            $cha['pshli'] = number_format($cha['pshli']);
                                        }else{
                                            $cha['pshli'] = '';
                                        }
                                        if($cha['shou'] != 0){
                                            $cha['shou'] = number_format($cha['shou']);
                                        }else{
                                            $cha['shou'] = '';
                                        }
                                        if($cha['pshou'] != 0){
                                            $cha['pshou'] = number_format($cha['pshou']);
                                        }else{
                                            $cha['pshou'] = '';
                                        }
                                        if(intval(str_replace(',', '', $cha['shou']))-intval(str_replace(',', '', $cha['pshou'])) != 0){
                                            $record = intval(str_replace(',', '', $cha['shou']))-intval(str_replace(',', '', $cha['pshou']));
                                            $cha['shou_new'] = number_format($record);
                                        }else{
                                            $cha['shou_new'] = '';
                                        }
                                        if(intval(str_replace(',', '', $cha['shli']))-intval(str_replace(',', '', $cha['pshli'])) != 0){
                                            $record = intval(str_replace(',', '', $cha['shli']))-intval(str_replace(',', '', $cha['pshli']));
                                            $cha['shli_new'] = number_format($record);
                                        }else{
                                            $cha['shli_new'] = '';
                                        }
                                ?>
                                    <tr>
                                        <td><?= $cha['date'] ?></td> 
                                        <td><?= $cha['pdate'] ?></td>     
                                        <td><?= $cha['ticker'] ?></td>     
                                        <td><?= $cha['pticker'] ?></td>     
                                        <td><?= $cha['market'] ?></td>
                                        <td><?= $cha['pmarket'] ?></td>   
                                        <td class="numeric"><?= $cha['shli'] ?></td>
                                        <td class="numeric"><?= $cha['pshli'] ?></td>  
                                        <td class="numeric"><?= $cha['shou'] ?></td>
                                        <td class="numeric"><?= $cha['pshou'] ?></td>
                                        <td class="numeric"><?= $cha['shou_new'] ?></td>
                                        <td class="numeric"><?= $cha['shli_new'] ?></td>                     
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </dd>
                    <dt><span class="number">+</span>From Exchanges</span><span style="float:right">Number: <?= $number_dividend ?></span></dt>
                    <dd>
                        <table class="table sortable table-ajax" footer="false" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('ticker'); ?>
                                    </th>
                                    <th width="5%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('market'); ?>
                                    </th>
                                    <th width="5%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('date') ?>
                                    </th>
                                    <th width="5%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('dividend') ?>
                                    </th>
                                    <th width="5%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('div_new') ?>
                                    </th>
                                    <th width="25%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('compare') ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($dividend as $div){
                                        if($div['dividend'] != 0){
                                            $div['dividend'] = number_format($div['dividend']);
                                        }else{
                                            $div['dividend'] = '';
                                        }
                                        if($div['div_news'] != ''){
                                            $div['div_news'] = number_format($div['div_news']);
                                        }else{
                                            $div['div_news'] = '';
                                        }
                                ?>
                                    <tr>
                                        <td><?= $div['ticker'] ?></td> 
                                        <td><?= $div['market'] ?></td>     
                                        <td><?= $div['date'] ?></td>  
                                        <td class="numeric"><?= $div['dividend'] ?></td>        
                                        <td class="numeric"><?= $div['div_news'] ?></td>  
                                        <td><?= $div['compare'] ?></td>           
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </dd>
                    <dt><span class="number">+</span>From News</span><span style="float:right">Number: <?= $number_ca ?></span></dt>
                    <dd>
                        <table class="table sortable table-ajax" footer="false" cellspacing="0" width="200%">
                            <thead>
                                <tr>
                                    <th width="3%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('ticker'); ?>
                                    </th>
                                    <th width="3%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('market'); ?>
                                    </th>
                                    <th width="5%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('events_type') ?>
                                    </th>
                                    <th width="5%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('date_ann') ?>
                                    </th>
                                    <th width="5%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('sh_old') ?>
                                    </th>
                                    <th width="5%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('sh_add') ?>
                                    </th>
                                    <th width="5%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('sh_new') ?>
                                    </th>
                                    <th width="7%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('sh_type') ?>
                                    </th>
                                    <th width="5%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('ipo_date') ?>
                                    </th>
                                    <th width="5%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('ftrd') ?>
                                    </th>
                                    <th width="5%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('date_ex') ?>
                                    </th>
                                    <th width="5%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('date_rec') ?>
                                    </th>
                                    <th width="5%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('date_pay') ?>
                                    </th>
                                    <th width="3%" scope="col" sType="string" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('ratio') ?>
                                    </th>
                                    <th width="3%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('year') ?>
                                    </th>
                                    <th width="5%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('period') ?>
                                    </th>
                                    <th width="3%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('prices') ?>
                                    </th>
                                    <th width="3%" scope="col" sType="numeric" bSortable="true">
                                        <span class="column-sort">
                                            <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                            <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                        </span>
                                        <?php trans('div') ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($ca as $item){
                                        if($item['sh_old'] != 0){
                                            $item['sh_old'] = number_format($item['sh_old']);
                                        }else{
                                            $item['sh_old'] =  '';
                                        }
                                        if($item['sh_add'] != 0){
                                            $item['sh_add'] = number_format($item['sh_add']);
                                        }else{
                                            $item['sh_add'] = '';
                                        }
                                        if($item['sh_new'] != 0){
                                            $item['sh_new'] = number_format($item['sh_new']);
                                        }else{
                                            $item['sh_new'] = '';
                                        }
                                        if($item['prices'] != 0){
                                            $item['prices'] = number_format($item['prices']);
                                        }else{
                                            $item['prices'] = '';
                                        }
                                        if($item['div'] != 0){
                                            $item['div'] = number_format($item['div']);
                                        }else{
                                            $item['div'] = '';
                                        }
                                        if($item['ftrd'] == '0000-00-00'){
                                            $item['ftrd'] = '';
                                        }
                                        if($item['date_ex'] == '0000-00-00'){
                                            $item['date_ex'] = '';
                                        }
                                        if($item['date_rec'] == '0000-00-00'){
                                            $item['date_rec'] = '';
                                        }
                                        if($item['date_pay'] == '0000-00-00'){
                                            $item['date_pay'] = '';
                                        }
                                ?>
                                    <tr>
                                        <td><?= $item['ticker'] ?></td> 
                                        <td><?= $item['market'] ?></td> 
                                        <td><?= $item['events_type'] ?></td> 
                                        <td><?= $item['date_ann'] ?></td> 
                                        <td class="numeric"><?= $item['sh_old'] ?></td> 
                                        <td class="numeric"><?= $item['sh_add'] ?></td>
                                        <td class="numeric"><?= $item['sh_new'] ?></td> 
                                        <td><?= $item['sh_type'] ?></td> 
                                        <td><?= $item['ipo_date'] ?></td> 
                                        <td><?= $item['ftrd'] ?></td>     
                                        <td><?= $item['date_ex'] ?></td>     
                                        <td><?= $item['date_rec'] ?></td>     
                                        <td><?= $item['date_pay'] ?></td>
                                        <td><?= $item['ratio'] ?></td>   
                                        <td><?= $item['year'] ?></td>
                                        <td><?= $item['period'] ?></td>  
                                        <td class="numeric"><?= $item['prices'] ?></td>
                                        <td class="numeric"><?= $item['div'] ?></td>                       
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </dd>
                </dl>
             
            </div>
        </form>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $("#date").datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: new Date(2013, 1 - 1, 1)
        });
    })
</script>
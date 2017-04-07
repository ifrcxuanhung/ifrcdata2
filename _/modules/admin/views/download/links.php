<style>
.ui-datepicker{
    z-index: 999 !important;
}
table{
    table-layout: auto;
}
td{
    word-wrap: break-word;
}
.green{
    background: -moz-linear-gradient(center top , white, #84DB7A 4%, #1A9B26) repeat scroll 0 0 transparent;
}
</style>
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form-table-ajax" id="" method="post" action="">
            <h1><?php trans('mn_donwload_links'); ?></h1>

            <div class="custom-btn" style="display:none;float: right; z-index: 200; position: relative;">
                <div id="select-area">
                    <button id="excute" type="button" class="with-tip green" name="excute" onclick="JavaScript: void(0)">Excute</button>
                    <?php
                        $cates = array('source', 'code_dwl', 'market', 'language', 'information', 'url', 'time', 'input', 'output');
                    ?>
                    <select id="cate-filter">
                        <option value="0"><span>Filter by...</span></option>
                <?php
                    foreach($cates as $item){
                        ?>  
                        <option value="<?php echo $item; ?>">&nbsp;&nbsp;&nbsp;>><?php echo $item; ?></option>
                        <?php
                    }
                ?>
                    </select>
                    <select id="detail-filter">
                        <option value="0"><span>Choose to filter...</span></option>
                    </select>
                </div>
            </div>
            <table class="table table-dividends-list table-ajax" cellspacing="0" width="100%" style="display: table">
                <thead>
                    <tr>
                        <th width="3%" class="sorting_disabled"><input type="checkbox" id="chk_all" /></th>
                        <th id="source" width="8%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('source'); ?>
                        </th>
                        <th id="code_dwl" width="10%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('code_dwl'); ?>
                        </th>
                        <th id="market" width="8%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('market') ?>
                        </th>
                        <th id="language" width="8%" scope="col" sType="numeric" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('language') ?>
                        </th>
                        <th id="information" width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('information') ?>
                        </th>
                        <th id="url" width="12%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('url') ?>
                        </th>
                        <th id="time" width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('time') ?>
                        </th>
                        <th id="output" width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('output') ?>
                        </th>
                        <th id="input" width="8%" scope="col" sType="string" bSortable="true">
                            <span class="column-sort">
                                <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                            </span>
                            <?php trans('input') ?>
                        </th>
                        <th width="15%" scope="col" class="table-actions" sType="" bSortable="false"><?php trans('actions'); ?></th>
                        <th width="15%" scope="col" class="table-actions" sType="" bSortable="false">Done</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </form>
    </div>
</section>
<script>

</script>

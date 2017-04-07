<style type="text/css">
div.dataTables_scroll { 
    clear: both; 
    margin-top: -1.667em;
}
.dataTables_scrollHeadInner{
    background: -moz-linear-gradient(center top , rgb(204, 204, 204), rgb(164, 164, 164)) repeat scroll 0px 0px transparent; 
    border-top: 1px solid white;
    margin-top: -1px;
}
.dataTables_scrollHeadInner table{
    display: block !important;
    height:50px;
    margin-top: -2px;
}
</style>
<section class="grid_12">
    <div class="block-border">
        <form class="block-content form" id="table_form" method="post" action="">
            <h1><?php trans('mn_compare') ?></h1>
            <div style="float: right; margin-right: 20px;" class="custom-btn">
                
                <div style="clear: left;"></div>
            </div>
            <div class="no-margin">
                <table id="table_compare" class="table no-margin" cellspacing="0" width="100%" pagination="false" >
                    <thead>
                        <tr>
                            <th width="47.5%" colspan="3" scope="col" sType="string" style="text-align:center">
                                <?php trans('title_exchange'); ?>
                            </th>
                            <th width="47.5%" colspan="3" scope="col" sType="string" style="text-align:center">
                                <?php trans('title_compare'); ?>
                            </th>
                            <th width="5%" colspan="3" scope="col" sType="string"></th>
                        </tr>
                        <tr>
                            <th width="10%" scope="col" sType="string" bSortable="true">
                                <?php trans('ticker'); ?>
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                            </th>
                            <th width="10%" scope="col" sType="string" bSortable="true">
                                <?php trans('date_ex'); ?>
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                            </th>
                            <th width="10%" scope="col" sType="string" bSortable="true">
                                <?php trans('div'); ?>
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                            </th>
                            <th width="10%" scope="col" sType="string" bSortable="true">
                                <?php trans('ticker'); ?>
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                            </th>
                            <th width="10%" scope="col" sType="string" bSortable="true">
                                <?php trans('date_ex'); ?>
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                            </th>
                            <th width="10%" scope="col" sType="numeric" bSortable="true">
                                <?php trans('div'); ?>
                                <span class="column-sort">
                                    <a href="#" title="<?php trans('sort_up'); ?>" class="sort-up"></a>
                                    <a href="#" title="<?php trans('sort_down'); ?>" class="sort-down"></a>
                                </span>
                            </th>
                            <th width="10%" scope="col" sType="string" bSortable="true">
                                <?php trans('title_compare'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</section>
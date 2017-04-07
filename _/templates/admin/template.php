<!doctype html>
<!--[if lt IE 8 ]><html lang="en" class="no-js ie ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie"><![endif]-->
<!--[if (gt IE 8)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
    <head>
        <!--
        <title>Administrator Panel - <?php echo ucwords($this->router->fetch_class()) . ' - ' . $title ?></title>
        -->
        <title><?php echo isset($this->registry->setting['meta_title']) ? $this->registry->setting['meta_title'] . ' | ' . ucwords($this->router->fetch_class()) : ''; ?></title>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo template_url(); ?>favicon.ico">
        <meta charset="utf-8" />
        <!-- Global stylesheets -->
        <?php
//        $arr_css = array(
//            'reset',
//            'common',
//            'form',
//            'standard',
//            '960.gs.fluid',
//            'simple-lists',
//            'block-lists',
//            'planning',
//            'table',
//            'calendars',
//            'wizard',
//            'gallery',
//            'menu'
//        );
//        $file_css = NULL;
//        foreach ($arr_css as $css) {
//            $file_css .= $css . ',';
//        }
//        $file_css = substr($file_css, 0, -1);
//        unset($arr_css);
        ?>
        <link href="<?php echo template_url(); ?>css/compress.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo template_url(); ?>css/style.css" rel="stylesheet" type="text/css" />

        <link href='<?php echo base_url(); ?>assets/bundles/jqGrid/css/ui.jqgrid.css' rel='stylesheet' type='text/css' media='screen' />
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/bundles/jqGrid/plugins/ui.multiselect.css" />
        <script src="<?php echo template_url(); ?>js/libs/modernizr.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/bundles/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/bundles/jquery.livequery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>assets/templates/backend/js/ZeroClipboard.min.js"></script>

        <script>
            var $template_url = '<?php echo template_url(); ?>';
            var $base_url = '<?php echo base_url(); ?>';
            var $admin_url = '<?php echo admin_url(); ?>';
            var $userid = '<?php echo $this->ion_auth->user()->row()->user_id; ?>';
            var $lang = "<?php echo $curent_language['code']; ?>";

            var $app = {'module': '<?php echo $this->router->fetch_module(); ?>',
                'controller': '<?php echo $this->router->fetch_class(); ?>',
                'action': '<?php echo $this->router->fetch_method(); ?>'};
        </script>

    </head>
    <?php
    //Tuan Anh update nxt_date follow calculation_dates when user login
    $this->load->model('Setting_model', 'setting_model');
    $nxt_dates = $this->setting_model->check_update_nxt_dates();
    if (isset($nxt_dates[0]['currdate']) && $nxt_dates[0]['currdate'] != '')
        $this->setting_model->update_nxt_dates();
    // ---------------- //
    ?>
    <body style="background: none;">
        <!-- The template uses conditional comments to add wrappers div for ie8 and ie7 - just add .ie or .ie7 prefix to your css selectors when needed -->
        <!--[if lt IE 9]><div class="ie"><![endif]-->
        <!--[if lt IE 8]><div class="ie7"><![endif]-->
        <!-- Header -->
        <!-- Server status -->
        <header id="myTop">
            <div class="container_12">
                <?php if (isset($list_language) && is_array($list_language)): ?>
                    <div class="server-info list-language">
                        <ul>
                            <?php foreach ($list_language as $value) : ?>
                                <li class="<?php echo $curent_language['code'] == $value['code'] ? 'active' : '' ?>"><a href="javascript:void(0)" langcode="<?php echo $value['code']; ?>"><img src="<?php echo template_url() ?>images/icons/flags/<?php echo $value['code']; ?>.png" width="16" height="11" alt="<?php echo $value['name']; ?>" title="<?php echo $value['name']; ?>"></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="server-info" style="float:left; background: #800000;">
                    <?php
                    if (isset($this->registry->setting['home_development_txt']) && $this->registry->setting['home_development_txt'] != '')
                        trans($this->registry->setting['home_development_txt']);
                    ?>
                </div>
                <!--
                <div class="server-info" style="float:left">
                    <a style="color:#FFF" href="<?php echo admin_url() . 'help'; ?>">Help</a>
                </div>
                -->
                <div class="server-info" id="ipaddr">
                    <?php echo trans('IP address:'); ?><span style="color: #FFF"><?php echo $_SERVER['REMOTE_ADDR']; ?></span>
                </div>
                <div class="server-info">
                    <?php echo trans('Date:'); ?><span style="color: #FFF"><?php echo date('Y - M - d', strtotime('now')); ?></span>
                </div>
                <div class="server-info">
                    <span><?php echo trans('Logged as:'); ?><span style="color: orange;"><?php echo $this->ion_auth->user()->row()->username; ?></span> | </span><a href="<?php echo base_url() . 'auth/logout' ?>" title="Logout" class="nav-button"><b>Logout</b></a>
                </div>
            </div>
            <h1 id="slogan">
                <?php
                if (isset($this->registry->setting['title']))
                    trans($this->registry->setting['title']);
                ?>
            </h1>
        </header>
        <!-- End server status -->
        <!-- Main nav -->
        <nav style="height:90px;"></nav>
        <div id="gkMainMenu">
            <div class="gk-menu">
                <ul class="gkmenu level0">
                    <li class="first">
                        <a href="<?php echo admin_url() . 'home'; ?>" class="first" title="Home"><span style="height:45px;width:1px;background:none;"></span><img src="<?php echo template_url(); ?>images/home.png" alt="" /></a>
                    </li>
                    <li class="haschild">
                        <a href="<?php echo admin_url() . 'users' ?>" title="Users"><span class="menu-title"></span><img src="<?php echo template_url(); ?>images/user_icon.png" alt="" /></a>
                        <div class="childcontent">
                            <div class="childcontent-inner-wrap normalSubmenu">
                                <div class="childcontent-inner">
                                    <ul class="gkmenu level1">
                                        <li class="first">
                                            <a href="<?php echo admin_url() . 'users' ?>" class=" first" title="Manage Users"><span class="menu-title"><?php trans('mn_Manage_User'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'group' ?>" class=" first" title="User Groups"><span class="menu-title"><?php trans('mn_User_Groups'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'role' ?>" title="Languages"><span class="menu-title"><?php trans('mn_Role'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'resource' ?>" title="Resource"><span class="menu-title"><?php trans('mn_Resource'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'permission' ?>" title="Permissions"><span class="menu-title"><?php trans('mn_Permissions'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'log' ?>" title="Log"><span class="menu-title"><?php trans('mn_Log'); ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="haschild">
                        <a href="#" title="Websites"><span class="menu-title"></span><img src="<?php echo template_url(); ?>images/websites_icon.png" alt="" /></a>
                        <div class="childcontent">
                            <div class="childcontent-inner-wrap normalSubmenu">
                                <div class="childcontent-inner">
                                    <ul class="gkmenu level1">
                                        <li class="first haschild">
                                            <a href="<?php echo admin_url() . 'menu' ?>" class=" first" title="Menus"><span class="menu-title"><?php trans('mn_Menu'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'page' ?>" title="Pages"><span class="menu-title"><?php trans('mn_Pages'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'category' ?>" title="Categories"><span class="menu-title"><?php trans('mn_Categories'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'article' ?>" title="Languages"><span class="menu-title"><?php trans('mn_Articles'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'media' ?>"  title="Media"><span class="menu-title"><?php trans('mn_Media'); ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="haschild">
                        <a href="#" title="Users"><span class="menu-title"></span><img src="<?php echo template_url(); ?>images/system_icon.png" alt="" /></a>
                        <div class="childcontent">
                            <div class="childcontent-inner-wrap normalSubmenu">
                                <div class="childcontent-inner">
                                    <ul class="gkmenu level1">
                                        <li class="first haschild">
                                            <a href="<?php echo admin_url() . 'setting' ?>" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_Settings'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'language' ?>" title="Languages"><span class="menu-title"><?php trans('mn_Languages'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'translate' ?>"  title="Translates"><span class="menu-title"><?php trans('mn_Translates'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . '#' ?>" title="Backup"><span class="menu-title"><?php trans('mn_Backup'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" onClick="file_manager()" class="first" title="Files"><span class="menu-title"><?php trans('mn_Files'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'synchronization' ?>"  title="Synchronization"><span class="menu-title"><?php trans('mn_synchronization'); ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="first">
                        <a href="<?php echo admin_url() . 'help' ?>" class="first" title="Help"><span style="height:45px;width:1px;background:none;"></span><img src="<?php echo template_url(); ?>images/help.png" alt="" /></a>
                    </li>

                    <li class="haschild">
                        <a href="#"><span class="menu-title"></span><?php trans('mn_VietNam'); ?></a>
                        <div class="childcontent">
                            <div class="childcontent-inner-wrap normalSubmenu">
                                <div class="childcontent-inner">
                                    <ul class="gkmenu level1">
                                        <!--<li class="first">
                                            <a href="<?php echo base_url(); ?>backend/sysformat/index?table=idx_composition" class=" first" title="Menus"><span class="menu-title"><?php trans('mn_Composition'); ?></span></a>
                                        </li>-->
                                        <li class="haschild">
                                            <a href="<?php echo admin_url(); ?>daily_action"><span class="menu-title"><?php trans('mn_Daily_Today'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="#" title="<?php trans('mn_AllINONE'); ?>"><span class="menu-title"><?php trans('mn_AllINONE'); ?></span></a>
                                                            </li>

                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_Reference_Daily'); ?>"><span class="menu-title"><?php trans('mn_Reference_Daily'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level2">
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url(); ?>reference/update_calendar" title="<?php trans('mn_Update_Calendar_Ref'); ?>"><span class="menu-title"><?php trans('mn_Update_Calendar_Ref'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>reference/reference_all" title="<?php trans('mn_Reference_All'); ?>"><span class="menu-title"><?php trans('mn_Reference_All'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>reference/reference_old" title="<?php trans('mn_Reference_Old'); ?>"><span class="menu-title"><?php trans('mn_Reference_Old'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>download/action" title="<?php trans('mn_Reference_New'); ?>"><span class="menu-title"><?php trans('mn_Reference_New'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>reference/reference_merges" title="<?php trans('mn_Reference_Merges'); ?>"><span class="menu-title"><?php trans('mn_Reference_Merges'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>reference/reference_export" title="<?php trans('mn_Reference_Export'); ?>"><span class="menu-title"><?php trans('mn_Reference_Export'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="#" title="<?php trans('mn_Report'); ?>"><span class="menu-title"><?php trans('mn_Report'); ?></span></a>
                                                                                    <div class="childcontent">
                                                                                        <div class="childcontent-inner-wrap normalSubmenu">
                                                                                            <div class="childcontent-inner">
                                                                                                <ul class="gkmenu level2">
                                                                                                    <li class="first haschild">
                                                                                                        <a href="<?php echo admin_url(); ?>reference/report_all" title="<?php trans('mn_Report_All'); ?>"><span class="menu-title"><?php trans('mn_Report_All'); ?></span></a>
                                                                                                    </li>
                                                                                                    <li class="haschild">
                                                                                                        <a href="<?php echo admin_url(); ?>reference/change" title="<?php trans('mn_Reference_Change'); ?>"><span class="menu-title"><?php trans('mn_Reference_Change'); ?></span></a>
                                                                                                    </li>
                                                                                                    <li class="haschild">
                                                                                                        <a id="reference-anomalies" href="javascript:void(0)" title="<?php trans('mn_Reference_anomalies'); ?>"><span class="menu-title"><?php trans('mn_Reference_anomalies'); ?></span></a>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>reference/reference_compare" title="<?php trans('mn_Reference_Compare'); ?>"><span class="menu-title"><?php trans('mn_Reference_Compare'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>reference/reference_switch" title="<?php trans('mn_Reference_Switch'); ?>"><span class="menu-title"><?php trans('mn_Reference_Switch'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="javascript: void(0);" class="down-shou-hnx"><span class="menu-title"><?php trans('mn_Shou_new'); ?></span></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_Prices_Daily'); ?>"><span class="menu-title"><?php trans('mn_Prices_Daily'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level2">
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url(); ?>prices/prices_all" title="<?php trans('mn_Prices_All'); ?>"><span class="menu-title"><?php trans('mn_Prices_All'); ?></span></a>
                                                                                </li>
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url(); ?>prices/prices_stoxfeed" title="<?php trans('mn_Prices_Stoxfeed'); ?>"><span class="menu-title"><?php trans('mn_Prices_Stoxfeed'); ?></span></a>
                                                                                </li>
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url(); ?>prices/upload_stp_prices" title="<?php trans('mn_upload_Prices_Stoxfeed'); ?>"><span class="menu-title"><?php trans('mn_upload_Prices_Stoxfeed'); ?></span></a>
                                                                                </li>
                                                                                 <li class="first haschild">
                                                                                    <a href="<?php echo admin_url(); ?>prices/compare_prices" title="<?php trans('mn_compare_Prices'); ?>"><span class="menu-title"><?php trans('mn_compare_Prices'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a class="download-prices" href="javascript:void(0)" title="<?php trans('mn_Prices_Dwl'); ?>"><span class="menu-title"><?php trans('mn_Prices_Dwl'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>prices/report_all" title="<?php trans('mn_Report_all'); ?>"><span class="menu-title"><?php trans('mn_Report_all'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>prices/prices_switch" title="<?php trans('mn_Prices_Switch'); ?>"><span class="menu-title"><?php trans('mn_Prices_Switch'); ?></span></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_Vndb_Daily'); ?>"><span class="menu-title"><?php trans('mn_Vndb_Daily'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level2">
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url(); ?>daily/both_merges_switch" title="<?php trans('mn_Vndb_All'); ?>"><span class="menu-title"><?php trans('mn_Vndb_All'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>daily/merges_daily" title="<?php trans('mn_Vndb_Merges'); ?>"><span class="menu-title"><?php trans('mn_Vndb_Merges'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>daily/daily_switch" title="<?php trans('mn_Vndb_Switch'); ?>"><span class="menu-title"><?php trans('mn_Vndb_Switch'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>daily/daily_all" title="<?php trans('mn_Vndb_2013'); ?>"><span class="menu-title"><?php trans('mn_Vndb_2013'); ?></span></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="haschild">
                                                                <a class="download-bloomberg" href="javascript:void(0)"><span class="menu-title"><?php trans('mn_Download_Currency'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_Dividend_Daily'); ?>"><span class="menu-title"><?php trans('mn_Dividend_Daily'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level2">
                                                                                <li>
                                                                                    <a class="download-dividend_daily" href="javascript:void(0)" title="<?php trans('mn_Dividend_Download'); ?>"><span class="menu-title"><?php trans('mn_Dividend_Download'); ?></span></a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="<?php echo admin_url(); ?>download/import_dividend_daily" title="<?php trans('mn_Dividend_Update'); ?>"><span class="menu-title"><?php trans('mn_Dividend_Update'); ?></span></a>
                                                                                </li> 
                                                                                <li>
                                                                                    <a href="javascript:void(0);" class="cpaction-update" title="<?php trans('mn_CPAction_Update'); ?>"><span class="menu-title"><?php trans('mn_CPAction_Update'); ?></span></a>
                                                                                </li>  
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li> 
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_Event_Daily'); ?>"><span class="menu-title"><?php trans('mn_Event_Daily'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level2">
                                                                                <li class="first haschild">
                                                                                    <a href="javascript:void(0);" class="download-event-hnx" title="<?php trans('mn_EventHNX_Download'); ?>"><span class="menu-title"><?php trans('mn_EventHNX_Download'); ?></span></a>
                                                                                </li>
                                                                                <!--li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>event/hsx" title="<?php trans('mn_EventHSX_Download'); ?>"><span class="menu-title"><?php trans('mn_EventHSX_Download'); ?></span></a>
                                                                                </li-->
                                                                                <li>
                                                                                    <a href="javascript:void(0);" class="download-event-hsx" title="<?php trans('mn_EventHSX_Download'); ?>"><span class="menu-title"><?php trans('mn_EventHSX_Download'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="javascript:void(0);" class="download-news-hsx" title="<?php trans('mn_NewsHSX_Download'); ?>"><span class="menu-title"><?php trans('mn_NewsHSX_Download'); ?></span></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo base_url(); ?>welcome/download_cafef_yesterday"><span class="menu-title"><?php trans('mn_CafefDWL_Yesterday'); ?></span></a>
                                                            </li>
                                                            <li class="first haschild">
                                                                <a href="#" title="<?php trans('mn_Download_Exchanges'); ?>"><span class="menu-title"><?php trans('mn_Download_Exchanges'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level2">
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url(); ?>prices" title="<?php trans('mn_Prices'); ?>"><span class="menu-title"><?php trans('mn_Prices'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>hsx_daily/index" title="<?php trans('mn_HSX'); ?>"><span class="menu-title"><?php trans('mn_HSX'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>hnx" title="<?php trans('mn_HNX'); ?>"><span class="menu-title"><?php trans('mn_HNX'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>upcom" title="<?php trans('mn_Upcom'); ?>"><span class="menu-title"><?php trans('mn_Upcom'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="#" title="<?php trans('mn_daily'); ?>"><span class="menu-title"><?php trans('mn_daily'); ?></span></a>
                                                                                    <div class="childcontent">
                                                                                        <div class="childcontent-inner-wrap normalSubmenu">
                                                                                            <div class="childcontent-inner">
                                                                                                <ul class="gkmenu level2">
                                                                                                    <li class="first haschild">
                                                                                                        <a href="<?php echo admin_url(); ?>file_daily"><span class="menu-title"><?php trans('mn_Prices_daily'); ?></span></a>
                                                                                                    </li>
                                                                                                    <!--<li class="haschild">
                                                                                                         <a href="<?php echo admin_url(); ?>file_daily/ref" title="<?php trans('mn_Reference_daily'); ?>"><span class="menu-title"><?php trans('mn_Reference_daily'); ?></span></a>
                                                                                                     </li>-->
                                                                                                    <li class="haschild">
                                                                                                        <a href="<?php echo admin_url() . 'steps/shares_daily' ?>" title="<?php trans('mn_Reference_daily'); ?>"><span class="menu-title"><?php trans('mn_Reference_daily'); ?></span></a>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="#" title="<?php trans('mn_Reference'); ?>"><span class="menu-title"><?php trans('mn_Reference'); ?></span></a>
                                                                                    <div class="childcontent">
                                                                                        <div class="childcontent-inner-wrap normalSubmenu">
                                                                                            <div class="childcontent-inner">
                                                                                                <ul class="gkmenu level2">
                                                                                                    <li class="first haschild">
                                                                                                        <a href="<?php echo admin_url() . 'hnx_ref' ?>" title="<?php trans('mn_HNX'); ?>"><span class="menu-title"><?php trans('mn_HNX'); ?></span></a>
                                                                                                    </li>
                                                                                                    <li class="haschild">
                                                                                                        <a href="<?php echo admin_url() . 'upc_ref' ?>" title="<?php trans('mn_UPC'); ?>"><span class="menu-title"><?php trans('mn_UPC'); ?></span></a>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url() . 'report/report_daily' ?>" title="<?php trans('mn_Report'); ?>"><span class="menu-title"><?php trans('mn_Report'); ?></span></a>
                                                                                </li>

                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_Other_Pages'); ?>"><span class="menu-title"><?php trans('mn_Other_Pages'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level2">
                                                                                <li class="first haschild">
                                                                                    <a href="#" title="<?php trans('mn_FPTS'); ?>"><span class="menu-title"><?php trans('mn_FPTS'); ?></span></a>
                                                                                </li>
                                                                                <li class="first haschild">
                                                                                    <a href="#" title="<?php trans('mn_CoPhieu68'); ?>"><span class="menu-title"><?php trans('mn_CoPhieu68'); ?></span></a>
                                                                                </li>
                                                                                <li class="first haschild">
                                                                                    <a href="#" title="<?php trans('mn_VietStock'); ?>"><span class="menu-title"><?php trans('mn_VietStock'); ?></span></a>
                                                                                </li>
                                                                                <li class="first haschild">
                                                                                    <a href="#" title="<?php trans('mn_Istock'); ?>"><span class="menu-title"><?php trans('mn_Istock'); ?></span></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo admin_url() . 'woman/get_ceo_all' ?>" title="<?php trans('mn_Women_CEO'); ?>"><span class="menu-title"><?php trans('mn_Women_CEO'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level2">
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url() . 'woman/download_ceo' ?>" title="<?php trans('mn_Download'); ?>"><span class="menu-title"><?php trans('mn_Download'); ?></span></a>
                                                                                </li>
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url() . 'woman/compare_ceo' ?>" title="<?php trans('mn_Compare'); ?>"><span class="menu-title"><?php trans('mn_Compare'); ?></span></a>
                                                                                </li>
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url() . 'woman/import_ceo' ?>" title="<?php trans('mn_Import'); ?>"><span class="menu-title"><?php trans('mn_Import'); ?></span></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="haschild">
                                            <a href="#"><span class="menu-title"><?php trans('mn_Weekly'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a class="download-dividend" href="javascript:void(0)" title="<?php trans('mn_Dividend_Dwl'); ?>"><span class="menu-title"><?php trans('mn_Dividend_Dwl'); ?></span></a>
                                                            </li>                                            
                                                            <li class="first haschild">
                                                                <a class="download-ownership" href="javascript:void(0)" title="<?php trans('mn_Ownership_Dwl'); ?>"><span class="menu-title"><?php trans('mn_Ownership_Dwl'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="haschild">
                                            <a href="<?php echo admin_url(); ?>statistics_daily"><span class="menu-title"><?php trans('mn_Stats'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="#" title="<?php trans('mn_Stats_Upload'); ?>"><span class="menu-title"><?php trans('mn_Stats_Upload'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level1">
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url(); ?>statistics/upload_day" title="<?php trans('mn_upload_daily'); ?>"><span class="menu-title"><?php trans('mn_upload_daily'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>statistics/upload_month" title="<?php trans('mn_upload_monthly'); ?>"><span class="menu-title"><?php trans('mn_upload_monthly'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>statistics/upload_year" title="<?php trans('mn_upload_yearly'); ?>"><span class="menu-title"><?php trans('mn_upload_yearly'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>statistics/upload_all" title="<?php trans('mn_create_stats_all'); ?>"><span class="menu-title"><?php trans('mn_create_stats_all'); ?></span></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_Stats_Download'); ?>"><span class="menu-title"><?php trans('mn_Stats_Download'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level1">
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url(); ?>download/get_exc" title="<?php trans('mn_download_daily'); ?>"><span class="menu-title"><?php trans('mn_download_daily'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="#" title="<?php trans('mn_download_monthly'); ?>"><span class="menu-title"><?php trans('mn_download_monthly'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="#" title="<?php trans('mn_download_yearly'); ?>"><span class="menu-title"><?php trans('mn_download_yearly'); ?></span></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo admin_url(); ?>report/export_report" title="<?php trans('mn_Stats_Update'); ?>"><span class="menu-title"><?php trans('mn_Stats_Update'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="haschild">
                                            <a href="#"><span class="menu-title"><?php trans('mn_Monthly'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url(); ?>report/report_month" title="<?php trans('mn_Exreport'); ?>"><span class="menu-title"><?php trans('mn_Exreport'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="haschild">
                                            <a href="#"><span class="menu-title"><?php trans('mn_Historical_Data'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url(); ?>hnx/custom_hnx" title="<?php trans('mn_HNX'); ?>"><span class="menu-title"><?php trans('mn_HNX'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo admin_url(); ?>hsx/custom_hsx" title="<?php trans('mn_HSX'); ?>"><span class="menu-title"><?php trans('mn_HSX'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo admin_url(); ?>upcom/custom_upcom" title="<?php trans('mn_UPCOM'); ?>"><span class="menu-title"><?php trans('mn_UPCOM'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_FPTS'); ?>"><span class="menu-title"><?php trans('mn_FPTS'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_Cophieu68'); ?>"><span class="menu-title"><?php trans('mn_Cophieu68'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_vcbs'); ?>"><span class="menu-title"><?php trans('mn_vcbs'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="haschild">
                                            <a href="#"><span class="menu-title"><?php trans('mn_Download_Histoday'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="#"><span class="menu-title"><?php trans('mn_Download'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url(); ?>profile/index" title="Check in"><span class="menu-title"><?php trans('mn_Dwl_Forsoures'); ?></span></a>
                                                            </li>
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url(); ?>download/links" title="Check in"><span class="menu-title"><?php trans('mn_Multilink'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="haschild">
                                            <a href=""><span class="menu-title"><?php trans('mn_Metastock'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url(); ?>exchange" title="<?php trans('mn_Exchanges'); ?>"><span class="menu-title"><?php trans('mn_Exchanges'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo admin_url(); ?>fpts/convert_excel" title="<?php trans('mn_FPTS'); ?>"><span class="menu-title"><?php trans('mn_FPTS'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo admin_url(); ?>vietstock" title="<?php trans('mn_VST_HTM'); ?>"><span class="menu-title"><?php trans('mn_VST_HTM'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo admin_url(); ?>xls" title="<?php trans('mn_VST_XLS'); ?>"><span class="menu-title"><?php trans('mn_VST_XLS'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    </li>
                    <li class="haschild">
                        <a href="#" title="<?php trans('mn_Observatory'); ?>"><span class="menu-title"><?php trans('mn_Observatory'); ?></span></a>
                        <div class="childcontent">
                            <div class="childcontent-inner-wrap normalSubmenu">
                                <div class="childcontent-inner">
                                    <ul class="gkmenu level1">
                                        <li class="first haschild">
                                            <a href="<?php echo base_url(); ?>backend/observatory/refresh_sample" class=" first" title="<?php trans('mn_Upload_Sample'); ?>" onclick='return confirmUpload();'><span class="menu-title"><?php trans('mn_Upload_Sample'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="#" title="<?php trans('mn_Download'); ?>"><span class="menu-title"><?php trans('mn_Download'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="#" class=" first" title="<?php trans('mn_Download_All'); ?>"><span class="menu-title"><?php trans('mn_Download_All'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo base_url(); ?>backend/observatory/download_reuters" class=" first" title="<?php trans('mn_Reuters'); ?>"><span class="menu-title"><?php trans('mn_Reuters'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_Boomberg'); ?>"><span class="menu-title"><?php trans('mn_Boomberg'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_Stoxx'); ?>"><span class="menu-title"><?php trans('mn_Stoxx'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_NYSE'); ?>"><span class="menu-title"><?php trans('mn_NYSE'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_Yahoo'); ?>"><span class="menu-title"><?php trans('mn_Yahoo'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" title="<?php trans('mn_Google'); ?>"><span class="menu-title"><?php trans('mn_Google'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="haschild">
                                            <a href="#" class="first" title="<?php trans('mn_Convert_Metastock'); ?>" onclick='return confirmUpload();'><span class="menu-title"><?php trans('mn_Convert_Metastock'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="<?= admin_url(); ?>observatory/import_economics" class="first" title="<?php trans('mn_import_economics'); ?>" ><span class="menu-title"><?php trans('mn_import_economics'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="<?= admin_url(); ?>observatory/import_currency" class="first" title="<?php trans('mn_import_currency'); ?>" ><span class="menu-title"><?php trans('mn_import_currency'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a id="import-equity" href="#" class="first" title="<?php trans('mn_import_equity'); ?>" > <span class="menu-title"><?php trans('mn_import_equity'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a id="import-equity" href="#" class="first" title="<?php trans('mn_Monthly'); ?>" > <span class="menu-title"><?php trans('mn_Monthly'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="<?= admin_url(); ?>report/export_vnx_monthly" title="<?php trans('mn_Export_VNX_Monthly'); ?>"><span class="menu-title"><?php trans('mn_Export_VNX_Monthly'); ?></span></a>
                                                            </li>
                                                            <li class="first haschild">
                                                                <a href="<?= admin_url(); ?>report/export_vnx_yearly" title="<?php trans('mn_Export_VNX_Yearly'); ?>"><span class="menu-title"><?php trans('mn_Export_VNX_Yearly'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li> 
                    <li class="haschild">
                        <a href="#" title="Data"><span class="menu-title"><?php trans('mn_Data'); ?></span></a>
                        <div class="childcontent">
                            <div class="childcontent-inner-wrap normalSubmenu">
                                <div class="childcontent-inner">
                                    <ul class="gkmenu level1">
                                        <li class="first">
                                            <a href="<?php echo admin_url(); ?>sysformat/show_view" title="View"><span class="menu-title"><?php trans('mn_View'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'import' ?>" class=" first" title="Import"><span class="menu-title"><?php trans('mn_Import'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'import/all' ?>" class="" title="Import all"><span class="menu-title"><?php trans('mn_Import_all'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'check_dowload' ?>" class="" title="<?php trans('mn_check_download'); ?>"><span class="menu-title"><?php trans('mn_check_download'); ?></span></a>
                                        </li>
                                        <li class="first haschild">
                                            <a href="javascript:void(0)" class="action-vndb-prices-history first" title="<?php trans('mn_vndb_prices_history'); ?>"><span class="menu-title"><?php trans('mn_vndb_prices_history'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li>
                                                                <a href="javascript:void(0)" class="action-insert-meta-prices" title="<?php trans('mn_insert_meta_prices'); ?>"><span class="menu-title"><?php trans('mn_insert_meta_prices'); ?></span></a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" class="action-update-references" title="<?php trans('mn_update_references'); ?>"><span class="menu-title"><?php trans('mn_update_references'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="action-export-qidx-mdata" title="<?php trans('mn_download_qidx_mdata_txt'); ?>"><span class="menu-title"><?php trans('mn_download_qidx_mdata_txt'); ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="haschild">
                        <a href="#" title="<?php trans('mn_STEPS'); ?>"><span class="menu-title"><?php trans('mn_STEPS'); ?></span></a>
                        <div class="childcontent">
                            <div class="childcontent-inner-wrap normalSubmenu">
                                <div class="childcontent-inner">
                                    <ul class="gkmenu level1">
                                        <li class="first haschild">
                                            <a href="<?php echo admin_url() . 'steps/update_indexes' ?>" class=" first" title="<?php trans('mn_UPDATE_INDEXES'); ?>"><span class="menu-title"><?php trans('mn_UPDATE_INDEXES'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?php echo admin_url() . 'steps/update_calendar' ?>" title="<?php trans('mn_UPDATE_CALENDAR'); ?>"><span class="menu-title"><?php trans('mn_UPDATE_CALENDAR'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="#"  title="<?php trans('mn_UPDATE_PRICES'); ?>"><span class="menu-title"><?php trans('mn_UPDATE_PRICES'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="#" title="<?php trans('mn_close_prices'); ?>"><span class="menu-title"><?php trans('mn_close_prices'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level2">
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url() . 'steps/update_prices_all' ?>" title="<?php trans('mn_import_all'); ?>"><span class="menu-title"><?php trans('mn_import_all'); ?></span></a>
                                                                                </li>
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url() . 'steps/import_prices' ?>" title="<?php trans('mn_import_prices'); ?>"><span class="menu-title"><?php trans('mn_import_prices'); ?></span></a>
                                                                                </li>
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url() . 'steps/import_prices_missing' ?>" title="<?php trans('mn_import_missing'); ?>"><span class="menu-title"><?php trans('mn_import_missing'); ?></span></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="first haschild">
                                                                <a href="#" title="<?php trans('mn_adj_pri'); ?>"><span class="menu-title"><?php trans('mn_adj_pri'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level1">
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url() . 'steps/update_adjusted_close' ?>" title="<?php trans('mn_import_adj_pri'); ?>"><span class="menu-title"><?php trans('mn_import_adj_pri'); ?></span></a>
                                                                                </li>
                                                                                <li class="first haschild">
                                                                                    <a href="#" title="<?php trans('mn_cal_return'); ?>"><span class="menu-title"><?php trans('mn_cal_return'); ?></span></a>
                                                                                    <div class="childcontent">
                                                                                        <div class="childcontent-inner-wrap normalSubmenu">
                                                                                            <div class="childcontent-inner">
                                                                                                <ul class="gkmenu level2">
                                                                                                    <li>
                                                                                                        <a href="javascript:void(0)" class="action-insert-data-update-return first" title=""><span class="menu-title"><?php trans('mn_insert_data'); ?></span></a>
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <a href="javascript:void(0)" class="action-clear-data-update-return first" title=""><span class="menu-title"><?php trans('mn_clear_data'); ?></span></a>
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <a href="javascript:void(0)" class="action-calculate-return-update-return first" title=""><span class="menu-title"><?php trans('mn_calculate_return'); ?></span></a>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                                <li class="first haschild">
                                                                                    <a href="javascript:void(0)" class="action-adjusted-price-update-return first" title=""><span class="menu-title"><?php trans('mn_adjusted_close'); ?></span></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                            <li class="first haschild">
                                                                <a href="#" title="<?php trans('mn_adj_coeff'); ?>"><span class="menu-title"><?php trans('mn_adj_coeff'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="#" title="Files"><span class="menu-title"><?php trans('mn_UPDATE_DIV'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url() . 'steps/update_dividend_all' ?>" title="Check in"><span class="menu-title"><?php trans('mn_DIV_all'); ?></span></a>
                                                            </li>
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url() . 'steps/update_dividend_import' ?>" title="Check in"><span class="menu-title"><?php trans('mn_DIV_import'); ?></span></a>
                                                            </li>
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url() . 'steps/update_dividend_clean' ?>" title="Check in"><span class="menu-title"><?php trans('mn_DIV_clean'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="#" class="first" title="Files"><span class="menu-title"><?php trans('mn_UPDATE_SHARES'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="#" title="Check in"><span class="menu-title"><?php trans('mn_import_shares'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level1">
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url() . 'steps/update_shares_import_all' ?>" title="<?php trans('mn_all_in_one'); ?>"><span class="menu-title"><?php trans('mn_all_in_one'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url() . 'steps/update_shares_import_shares' ?>" title="<?php trans('mn_shares_dwl'); ?>"><span class="menu-title"><?php trans('mn_shares_dwl'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url() . 'steps/update_shares_import_references' ?>" title="<?php trans('mn_reference_dwl'); ?>"><span class="menu-title"><?php trans('mn_reference_dwl'); ?></span></a>
                                                                                </li>

                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="first haschild">
                                                                <a href="#" title="Check in"><span class="menu-title"><?php trans('mn_update_to_prices'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level1">
                                                                                <li class="first haschild">
                                                                                    <a href="<?php echo admin_url() . 'steps/update_shares_update_all' ?>" title="<?php trans('mn_all_in_one'); ?>"><span class="menu-title"><?php trans('mn_all_in_one'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url() . 'steps/update_shares_update_shli' ?>" title="<?php trans('mn_update_shli'); ?>"><span class="menu-title"><?php trans('mn_update_shli'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="<?php echo admin_url() . 'steps/update_shares_update_shou' ?>" title="<?php trans('mn_update_shou'); ?>"><span class="menu-title"><?php trans('mn_update_shou'); ?></span></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url() . 'steps/update_shares_clean' ?>" title="Check in"><span class="menu-title"><?php trans('mn_clean_shares'); ?></span></a>
                                                            </li>
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url() . 'steps/update_missing_shares' ?>" title="Check in"><span class="menu-title"><?php trans('mn_missing_shares'); ?></span></a>
                                                            </li>
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url() . 'steps/update_anomalies_share' ?>"><span class="menu-title"><?php trans('mn_cal_anomalies_share'); ?></span></a>
                                                            </li>
                                                            <li class="first">
                                                                <a href="javascript:void(0)" class="action-update-shares first" title=""><span class="menu-title"><?php trans('mn_update_shares_tuananh'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="haschild">
                                            <a href="#"><span class="menu-title"><?php trans('mn_cal_free_loat'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url() . 'steps/ownership_all' ?>" title="<?php trans('mn_ownership_all'); ?>"><span class="menu-title"><?php trans('mn_ownership_all'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo admin_url() . 'steps/import_ownership' ?>" title="<?php trans('mn_import_ownership'); ?>"><span class="menu-title"><?php trans('mn_import_ownership'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo admin_url() . 'steps/update_free_float' ?>"><span class="menu-title"><?php trans('mn_cal_free_float'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="#" title="Backup"><span class="menu-title"><?php trans('mn_UPDATE_STATS'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="#" title="Files"><span class="menu-title"><?php trans('mn_UPDATE_SPL'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="#" title="Files"><span class="menu-title"><?php trans('mn_UPDATE_SHR'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="#" title="Files"><span class="menu-title"><?php trans('mn_Report'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url(); ?>vndb_report" title="Check in"><span class="menu-title"><?php trans('mn_Report_Today'); ?></span></a>
                                                            </li>
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url(); ?>vndb_report_history" title="Check in"><span class="menu-title"><?php trans('mn_Report_Histoday'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="haschild">
                        <a href="#" title="Users"><span class="menu-title"><?php trans('mn_Mdata_Step'); ?></span></a>
                        <div class="childcontent">
                            <div class="childcontent-inner-wrap normalSubmenu">
                                <div class="childcontent-inner">
                                    <ul class="gkmenu level1">
                                        <li class="first haschild">
                                            <a href="<?= admin_url() ?>mdata/price_history" title="<?php trans('mn_VNDB_PRICES_HISTORY'); ?>"><span class="menu-title"><?php trans('mn_VNDB_PRICES_HISTORY'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="<?= admin_url() ?>mdata/update_dividend" title="<?php trans('mn_Update_Dividens'); ?>"><span class="menu-title"><?php trans('mn_Update_Dividens'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="<?= admin_url() ?>mdata/create_qidx"><span class="menu-title"><?php trans('mn_Export_QIDX_MDATA'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="<?= admin_url() ?>mdata/update_event"><span class="menu-title"><?php trans('mn_Create_Vndb_Event'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="<?= admin_url() ?>mdata/create"><span class="menu-title"><?php trans('mn_Create_VNDB_ADJCLS'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="<?= admin_url() ?>mdata/calculation"><span class="menu-title"><?php trans('mn_Caculation_Adjclose'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="<?= admin_url() ?>mdata/update"><span class="menu-title"><?php trans('mn_Update_Adjclose'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="<?php echo admin_url(); ?>calendar"><span class="menu-title"><?php trans('mn_Export_Calendar'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="<?php echo admin_url(); ?>currency"><span class="menu-title"><?php trans('mn_Export_Currency'); ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="haschild">
                        <a href="#" title="Users"><span class="menu-title"><?php trans('mn_vnxindexes'); ?></span></a>
                        <div class="childcontent">
                            <div class="childcontent-inner-wrap normalSubmenu">
                                <div class="childcontent-inner">
                                    <ul class="gkmenu level1">
                                        <li class="first haschild">
                                            <a href="#" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_performance'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url(); ?>performance/update_month_year" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_update_M/Y'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo admin_url(); ?>performance/chart" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_chart'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="haschild">
                                            <a href="<?php echo admin_url(); ?>vnx/hight_dividend" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_hight_dividend'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="#" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_volat'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url(); ?>vnx/low_volalitily" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_low_volat'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo admin_url(); ?>vnx/hight_volalitily" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_hight_volat'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="first haschild">
                                            <a href="#" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_equal_weight'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="<?php echo admin_url(); ?>vnx/equal_weighted_50" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_equal_weight_50'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="<?php echo admin_url(); ?>vnx/equal_weighted_25" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_equal_weight_25'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="haschild">
                        <a href="#" title="ETFDB"><span class="menu-title"><?php trans('mn_ETFDB'); ?></span></a>
                        <div class="childcontent">
                            <div class="childcontent-inner-wrap normalSubmenu">
                                <div class="childcontent-inner">
                                    <ul class="gkmenu level1">
                                        <li class="first haschild">
                                            <a href="<?= admin_url() ?>etf/download_etf_country" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_Download_countries'); ?></span></a>
                                        </li>
                                        <li class="haschild">
                                            <a href="<?= admin_url() ?>etf/download_etf_screener" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_Download_screener'); ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="haschild">
                        <a href="#" title="Users"><span class="menu-title"><?php trans('mn_IMS_Data'); ?></span></a>
                        <div class="childcontent">
                            <div class="childcontent-inner-wrap normalSubmenu">
                                <div class="childcontent-inner">
                                    <ul class="gkmenu level1">
                                        <li class="first haschild">
                                            <a href="<?= admin_url() ?>events" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_Events'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="<?= admin_url() ?>events/corporate" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_Corporate_Events'); ?></span></a>

                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_Dividends'); ?></span></a>
                                                            </li>
                                                            <li class="haschild">
                                                                <a href="#" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_Download'); ?></span></a>
                                                                <div class="childcontent">
                                                                    <div class="childcontent-inner-wrap normalSubmenu">
                                                                        <div class="childcontent-inner">
                                                                            <ul class="gkmenu level2">
                                                                                <li class="first haschild">
                                                                                    <a href="javascript:void(0);" class="download-event-hnx" title="<?php trans('mn_EventHNX_Download'); ?>"><span class="menu-title"><?php trans('mn_EventHNX_Download'); ?></span></a>
                                                                                </li>
                                                                                <!--li class="haschild">
                                                                                    <a href="<?php echo admin_url(); ?>event/hsx" title="<?php trans('mn_EventHSX_Download'); ?>"><span class="menu-title"><?php trans('mn_EventHSX_Download'); ?></span></a>
                                                                                </li-->
                                                                                <li>
                                                                                    <a href="javascript:void(0);" class="download-event-hsx" title="<?php trans('mn_EventHSX_Download'); ?>"><span class="menu-title"><?php trans('mn_EventHSX_Download'); ?></span></a>
                                                                                </li>
                                                                                <li class="haschild">
                                                                                    <a href="javascript:void(0);" class="download-news-hsx" title="<?php trans('mn_NewsHSX_Download'); ?>"><span class="menu-title"><?php trans('mn_NewsHSX_Download'); ?></span></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="<?= admin_url() ?>events/prepare" class="download-news-hsx" title="<?php trans('mn_Prepare_CA'); ?>"><span class="menu-title"><?php trans('mn_Prepare_CA'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= admin_url() ?>events/view" class="download-news-hsx" title="<?php trans('mn_View_CA'); ?>"><span class="menu-title"><?php trans('mn_View_CA'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="#" title="<?php trans('mn_Cash_Dividend'); ?>"><span class="menu-title"><?php trans('mn_Cash_Dividend'); ?></span></a>
                                            <div class="childcontent">
                                                <div class="childcontent-inner-wrap normalSubmenu">
                                                    <div class="childcontent-inner">
                                                        <ul class="gkmenu level1">
                                                            <li class="first haschild">
                                                                <a href="#" class=" first" title="Settings"><span class="menu-title"><?php trans('mn_Compare'); ?></span></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="<?= admin_url() ?>maintenance"title="<?php trans('mn_Daily_Maintenance'); ?>"><span class="menu-title"><?php trans('mn_Daily_Maintenance'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= admin_url() ?>update/compo"title="<?php trans('mn_Update_Compo'); ?>"><span class="menu-title"><?php trans('mn_Update_Compo'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= admin_url() ?>update/specs"title="<?php trans('mn_Update_Specs'); ?>"><span class="menu-title"><?php trans('mn_Update_Specs'); ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="haschild">
                        <a href="#" title="ETFDB"><span class="menu-title"><?php trans('mn_VNX_Data'); ?></span></a>
                        <div class="childcontent">
                            <div class="childcontent-inner-wrap normalSubmenu">
                                <div class="childcontent-inner">
                                    <ul class="gkmenu level1">
                                        <li>
                                            <a href="<?= admin_url() ?>update/vnx"title="<?php trans('mn_Update_Vnx'); ?>"><span class="menu-title"><?php trans('mn_Update_Vnx'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= admin_url() ?>update/update_stk_perf"title="<?php trans('mn_Update_Stk_Perf'); ?>"><span class="menu-title"><?php trans('mn_Update_Stk_Perf'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= admin_url() ?>update/world_indexes"title="<?php trans('mn_Update_World_Indexes'); ?>"><span class="menu-title"><?php trans('mn_Update_World_Indexes'); ?></span></a>
                                        </li>
                                        <!--<li>
                                            <a href="<?= admin_url() ?>update/ifrclab"title="<?php trans('mn_Update_IFRCLAB'); ?>"><span class="menu-title"><?php trans('mn_Update_IFRCLAB'); ?></span></a>
                                        </li>-->
                                        <li>
                                            <a href="<?= admin_url() ?>update/update_ifrc_lab2"title="<?php trans('mn_Update_IFRCLAB2'); ?>"><span class="menu-title"><?php trans('mn_Update_IFRCLAB2'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= admin_url() ?>update/update_womenceo_asia"title="<?php trans('mn_Womenceo_asia'); ?>"><span class="menu-title"><?php trans('mn_Womenceo_asia'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= admin_url() ?>update/update_all"title="<?php trans('mn_Update_ALL'); ?>"><span class="menu-title"><?php trans('mn_Update_ALL'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= admin_url() ?>update/back_data"title="<?php trans('mn_Update_Back_Data'); ?>"><span class="menu-title"><?php trans('mn_Update_Back_Data'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= admin_url() ?>update/get_hnx"title="<?php trans('mn_Download_HNX'); ?>"><span class="menu-title"><?php trans('mn_Download_HNX'); ?></span></a>
                                        </li>
                                        <li>
                                            <a href="<?= admin_url() ?>update/get_hsx"title="<?php trans('mn_Download_HSX'); ?>"><span class="menu-title"><?php trans('mn_Download_HSX'); ?></span></a>
                                        </li>
										<li>
                                            <a href="<?= admin_url() ?>update/update_currency_index"title="<?php trans('mn_Update_CUR'); ?>"><span class="menu-title"><?php trans('mn_Update_CUR'); ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- end menu -->
        <!-- Status bar -->
        <div id="status-bar"><div class="container_12">
                <ul id="breadcrumb">
                    <li><a href="#" title="<?php trans('DMS'); ?>"><?php trans('DMS'); ?></a></li>
                    <?php if ($this->router->fetch_class() != 'admin'): ?>
                        <li><a style="text-transform: capitalize" href="<?php echo admin_url() . $this->router->fetch_class(); ?>" title="<?php trans('mn_' . $this->router->fetch_class()); ?>"><?php trans('mn_' . $this->router->fetch_class()); ?></a></li>
                    <?php endif; ?>
                    <?php if ($this->router->fetch_method() != 'index'): ?>
                        <li><a style="text-transform: capitalize" href="#" title="<?php trans('mn_' . $title); ?>"><?php trans('mn_' . $title); ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <!-- End status bar -->
        <div id="header-shadow"></div>
        <!-- End header -->
        <!-- Always visible control bar -->
        <!-- End control bar -->
        <!-- Content -->
        <!--[if lt IE 8]></div><![endif]-->
        <!--[if lt IE 9]></div><![endif]-->
        <article class="container_12 main-container">
            <?php echo $content ?>
            <div class="clear"></div>
        </article>
        <footer>
            <div class="float-left">

            </div>

            <div class="float-right">
                <a href="#" class="button">
                    <?php
                    if (isset($this->registry->setting['home_bottom_txt']))
                        trans($this->registry->setting['home_bottom_txt']);
                    ?>
                </a>
                <!--
                <a href="#myTop" class="button">
                    <img src="<?php echo template_url(); ?>images/icons/fugue/navigation-090.png" width="16" height="16">
                    Page top
                </a>
                -->
            </div>

        </footer>
        <div id="lean_overlay" style="position: fixed;">
            <section class="grid_12" id="calculatingBlock">
                <span>
                    <center>
                        <?php trans('calculating'); ?><br><img width="100" src="<?php echo template_url(); ?>images/preloader.gif">
                    </center>
                </span>
            </section>
        </div>
        <!-- load javascript --------------------------------------------------------------------  -->
        <!-- Generic libs -->
        <script src="<?php echo template_url(); ?>js/old-browsers.js"></script>
        <!-- remove if you do not need older browsers detection -->
        <script src="<?php echo template_url(); ?>js/libs/jquery.hashchange.js"></script>
        <!-- Template libs -->
        <script src="<?php echo template_url(); ?>js/jquery.accessibleList.js"></script>
        <script src="<?php echo template_url(); ?>js/searchField.js"></script>
        <script src="<?php echo template_url(); ?>js/common.js"></script>
        <script src="<?php echo template_url(); ?>js/standard.js"></script>
        <!--[if lte IE 8]><script src="js/standard.ie.js"></script><![endif]-->
        <script src="<?php echo template_url(); ?>js/jquery.tip.js"></script>
        <script src="<?php echo template_url(); ?>js/jquery.contextMenu.js"></script>
        <script src="<?php echo template_url(); ?>js/jquery.modal.js"></script>
        <!-- Custom styles lib -->
        <script src="<?php echo template_url(); ?>js/list.js"></script>
        <!-- Plugins -->
        <script src="<?php echo template_url(); ?>js/highstocks.js"></script>
        <script src="<?php echo template_url(); ?>js/libs/jquery.dataTables.min.js"></script>
        <script src="<?php echo template_url(); ?>js/libs/dataTables.formattedNum.js"></script>
        <script src="<?php echo template_url(); ?>js/libs/dataTables.fnSetFilteringPressEnter.js"></script>
        <script src="<?php echo template_url(); ?>js/libs/jquery.datepick/jquery.datepick.min.js"></script>
        <script src="<?php echo template_url(); ?>js/mootools-core.js"></script>
        <script src="<?php echo template_url(); ?>js/menu.gkmenu.js"></script>
        <script src="<?php echo template_url(); ?>js/jquery.dataTables.columnFilter.js"></script>
        <script src="<?php echo base_url(); ?>assets/bundles/jquery-ui/jquery-ui-1.8.22.custom.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bundles/jquery-ui/css/jquery-ui-1.8.1.custom.css" />
        <script src="<?php echo base_url(); ?>assets/bundles/jquery.mousewheel-3.0.6.pack.js" ></script>
        <script src="<?php echo base_url(); ?>assets/bundles/fancyBox/jquery.fancybox.pack.js" ></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/bundles/fancyBox/jquery.fancybox.css" />
        <script src="<?php echo base_url(); ?>assets/bundles/sisyphus.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>assets/bundles/jqGrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
        <script src='<?php echo base_url(); ?>assets/bundles/jqGrid/js/jquery.jqGrid.min.js' type="text/javascript"></script>
        <script data-main="<?php echo base_url(); ?>assets/apps/backend/main" src="<?php echo base_url(); ?>assets/bundles/require.js"></script>
        <script>
                                                function showHidden(div1, div2) {
                                                    $(div1).focus(function() {
                                                        $(div2).slideDown();
                                                    });
                                                }
        </script>
    </body>
</html>
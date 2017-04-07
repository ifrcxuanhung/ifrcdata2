<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title><?php echo $title ?></title>
        <link href="<?php echo base_url() . 'public/default/' ?>images/favicon.png" rel="shortcut icon"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'public/default/' ?>css/style.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo base_url() . 'public/default/' ?>css/layout.css" type="text/css" />
        <link rel='stylesheet' id='nivoslider-css'  href='<?php echo base_url() . 'public/default/' ?>css/nivo-slider.css' type='text/css' media='all' />
        <link rel="stylesheet" href="<?php echo base_url() . 'public/default/' ?>css/prettyPhoto.css" type="text/css"/>
        <!-- start css menu main -->
        <link rel="stylesheet" href="<?php echo base_url() . 'public/default/' ?>css/menu.css" type="text/css"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'public/default/' ?>css/menuStyle.css" type="text/css"/>
        <!-- Default CSS File -->
        <link rel="stylesheet" href="<?php echo base_url() . 'public/default/' ?>css/typography.css" />

            <!--[if IE 6]><link href="<?php echo base_url() . 'public/default/' ?>css/ie6_css.css" rel="stylesheet" type="text/css" /><![endif]-->
            <!--[if IE 7]><link href="<?php echo base_url() . 'public/default/' ?>css/ie7_css.css" rel="stylesheet" type="text/css" /><![endif]-->
        <link rel="stylesheet" href="<?php echo base_url() . 'public/default/' ?>css/megamenu.css" type="text/css" media="screen" />
        <!-- start script main menu -->
        <script src="<?php echo base_url() . 'public/' ?>js/jquery-1.6.2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url() . 'public/' ?>js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
        <link rel="stylesheet" href="<?php echo base_url() . 'public/' ?>css/ui-lightness/jquery-ui-1.8.16.custom.css" type="text/css"/>


        <script type="text/javascript" src="<?php echo base_url() . 'public/default/' ?>js/megamenu.js"></script>
        <!-- Mega Menu Script -->

        <!-- script slider_products -->
        <script type="text/javascript" src="<?php echo base_url() . 'public/default/' ?>js/jquery.bxSlider.js"></script>
        <!-- start script slide main -->
        <script type='text/javascript' src='<?php echo base_url() . 'public/default/' ?>js/custom.js'></script>
        <script type='text/javascript' src='<?php echo base_url() . 'public/default/' ?>js/jquery.nivo.slider.js'></script>
        <script type="text/javascript" src="<?php echo base_url() . 'public/default/' ?>js/jquery.easing.1.3.js"></script>


        <script type="text/javascript">
            $GKMenu = { height: true, width: true, duration: 250 };
	
            $(document).ready(function() {
                $('#partner').bxSlider({
                    auto: true,
                    displaySlideQty: 5,
                    moveSlideQty: 1
                });
                
                $('.intro_pro').hover(function(){
                    var _this = this;

                    $(".details", this).stop().animate({bottom:'-15px', opacity:'1'},{queue:false,duration:500, easing: 'easeOutQuint', complete: function(){
                            $(".overlay", _this).css({overflow:"visible"});
                        }
                    });
                    $(".entry_title", this).stop().animate({bottom:'-280px', opacity:'0'},{queue:false,duration:500, easing: 'easeOutQuint'});
		
                }, function() {
                    //$(".overlay", this).css({overflow:"hidden"});
                    $(".details", this).stop().animate({bottom:'280px', opacity:'0'},{queue:false,duration:1000, easing: 'easeOutBack'});
                    $(".entry_title", this).stop().animate({bottom:'10px', opacity:'1'},{queue:false,duration:600, easing: 'easeOutBack'});
                });
                
                var $featured = jQuery('#product-slider'),
                $featured_content = jQuery('#product-slides'),
                $controller = jQuery('#product-thumbs'),
                $slider_control_tab = $controller.find('a');
                if ($featured_content.length) {
                    $featured_content.cycle({
                        fx: 'fade',
                        timeout: 0,
                        speed: 700,
                        cleartypeNoBg: true
                    });
			
                    var ordernum;				
			
                    function gonext(this_element){
                        $controller.find("a.active").removeClass('active');
				
                        this_element.addClass('active');
				
                        ordernum = this_element.attr("rel");
                        $featured_content.cycle(ordernum-1);
				
                        if (typeof interval != 'undefined') {
                            clearInterval(interval);
                            auto_rotate();
                        };
                    }
			
                    $slider_control_tab.click(function(){
                        gonext(jQuery(this));
                        return false;
                    });
                }
                // prettyphoto init
                jQuery("#product-slides a[rel^='prettyPhoto']").prettyPhoto({
                    animationSpeed:'slow',
                    theme:'facebook',
                    slideshow:false,
                    autoplay_slideshow: false,
                    show_title: true,
                    overlay_gallery: false
                });
            });
        </script>
        <script type='text/javascript' src='<?php echo base_url() . 'public/default/' ?>js/cufon-yui.js'></script>
        <script type='text/javascript' src='<?php echo base_url() . 'public/default/' ?>js/Rockwell-ExtraBold_700.font.js'></script>
        <script type="text/javascript">
            Cufon.replace	('h1,h2,.bt_buy > a');
        </script>
        <script type="text/javascript" src="<?php echo base_url() . 'public/default/' ?>js/jquery.cycle.all.min.js"></script>
        <script type='text/javascript' src='<?php echo base_url() . 'public/default/' ?>js/jquery.prettyPhoto.js'></script>
        <script type='text/javascript' src='<?php echo base_url() . 'public/default/' ?>js/jquery.validate.min.js'></script>
        <style>
            .dm_top li{
                position: relative;
            }
            #menu_top ul.sub_menu {
                display:none;   
                position:absolute;
                top: 27px;
                left: 1px;    
                background: #012;
            }
            .sub_menu a:hover {
                text-decoration: underline !important;
            }

            #menu_top ul.sub_menu li, #menu_top ul.sub_menu li a {
                width:130px;
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.dm_top li ').hover(
                function () {
                    $(this).find('.sub_menu').show();
                }, 
                function () {
                    $(this).find('.sub_menu').hide();
                }
            );
            });
        </script>
    </head>
    <body> 
        <!-- start header -->
        <div id="header">
            <!-- start top -->
            <div id="top">
                <!-- start menu top -->
                <div id="menu_top">
                    <ul  style="float:left">
                        <li><a class="active" href="#">Chào mừng 
                                <?php
                                if ($this->session->userdata('username'))
                                    echo $this->session->userdata('username'); else
                                    echo 'bạn';
                                ?> đến với website Ô Khuyến Mãi</a></li>
                    </ul>
                    <ul style="float:right" class="dm_top">

                        <?php
                        if ($this->session->userdata('username')) {

                            echo '<li><a href="' . base_url('user/logout') . '">' . $this->session->userdata('username') . ' (Thoát)</a></li>';

                            echo '<li ><a href="#" class="active">Quản lý cá nhân</a>';
                            echo '<ul class="sub_menu">';
                            echo '<li ><a href="' . base_url('user/edit') . '" >Thông tin</a></li>';
                            echo '<li ><a href="' . base_url('user/products') . '" >Sản phẩm</a></li>';
                            echo '</ul></li>';
                        } else {
                            ?>                            
                            <li><a class="active" href="<?php echo base_url('user/login') ?>">Đăng nhập</a></li>
                            <li><a href="<?php echo base_url('user/register') ?>">Đăng ký</a></li>
                            <li><a href="<?php echo base_url('contact') ?>">Liên hệ</a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <!-- end menu top -->
            </div>
            <!-- end top -->
            <!-- start banner -->
            <div id="banner">
                <!-- fix size banner menu... -->
                <div id="fix_size_banner">

                    <!-- start logo -->
                    <div id="logo">
                        <a href="<?php echo base_url() ?>"><img src="<?php echo base_url() . 'public/default/' ?>images/logo.png" /></a>
                    </div>
                    <!-- end logo -->
                    <!-- start search top -->
                    <div id="search_top">
                        <form action="#" method="get">
                            <input type="text" value="--Nhập thông tin tìm kiếm --" class="fix_txt_search" />
                            <div class="bt_search_top"><a href="#">Tìm kiếm</a></div>
                        </form>
                    </div>
                    <!-- end search top -->
                    <!-- start menu main -->
                    <div id="menu_main">
                        <div class="megamenu_container menu_blue">                            
                            <?php
                            echo $menu;
                            ?>
                        </div>
                    </div>
                    <!-- end menu main -->
                </div>
                <!-- end fix size banner menu... -->
            </div>
            <!-- end banner -->

        </div>
        <!-- end header -->
        <!-- fix size -->
        <div id="fix_size">


            <!-- start main content -->
            <div id="main">

                <?php
                if ($slider)
                    echo $slider . '<div style="clear:both; height:1px;">&nbsp;</div>';

                // array('0' => array('name' => 'abc', 'link' => 'def'),'1' => array('name' => 'fgh', 'link' => 'omnb'))
                if ($breadcrumb) {
                    ?>
                    <!--Breadcrumb-->
                    <div id="breadcrumb">
                        <ul>
                            <li class="breadcrumb-home"><a href="<?php echo base_url() ?>">Home</a></li>
                            <?php
                            echo $breadcrumb;
                            ?>                        
                        </ul>                    
                    </div>
                    <div style="clear:both; height:1px;">&nbsp;</div>
                    <!-- #breadcrumb -->
                    <?php
                }

                echo $content;

                if ($left || $right) {
                    echo '<div style="clear:both; height:1px;">&nbsp;</div>
                            <!-- start main child -->
                            <div id="main_child">';
                    echo $left;
                    echo $right;
                    echo '</div>
                            <!-- end main child -->
                            <div style="clear:both; height:1px;">&nbsp;</div>';
                }
                ?>
            </div>

            <!-- end main content -->
            <!-- start bottom -->
            <div id="bottom">
                <h1>Thông tin</h1>
                <div class="shadow_title_bot"></div>
                <div class="bt_info">
                    <ul>
                        <li class="even">
                            <h4><a href="#" >Về chúng tôi</a></h4>
                        </li>
                        <li class="odd">
                            <h4><a href="#" >Cho doanh nghiệp</a></h4>
                        </li>
                        <li class="even">
                            <h4><a href="#" >Tuyển dụng</a></h4>
                        </li>                        
                    </ul>
                </div>
                <div class="bt_info">
                    <ul>                        
                        <li class="odd">
                            <h4><a href="#" >Hot Deal</a></h4>
                        </li>
                        <li class="even">
                            <h4><a href="#" >Giá tốt hôm nay</a></h4>
                        </li>
                        <li class="odd">
                            <h4><a href="#" >Giá tốt gần đây</a></h4>
                        </li>
                    </ul>
                </div>
                <div class="bt_info">
                    <ul>
                        <li class="even">
                            <h4><a href="#" >Hướng dẫn</a></h4>
                        </li>
                        <li class="odd">
                            <h4><a href="#" >Thoả thuận sử dụng</a></h4>
                        </li>
                        <li class="even">
                            <h4><a href="#" >Liên hệ với chúng tôi</a></h4>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- end bottom -->
            <!-- start footer -->      
            <div id="footer" class="clearfix">
                <div id="footer_menu">
                    <p>Copyright 2012</p>
                </div>
                <!-- Copyright Information -->
                <div id="informations">Developed by <b>IFRC</b></div>
            </div>
            <!-- end footer -->
        </div> 
        <!-- end fix size -->
        <div id="fot_bg"></div>

    </body>
</html>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $title ?></title>
        <meta charset="utf-8">

        <!-- Combined stylesheets load -->
        <!-- Load either 960.gs.fluid or 960.gs to toggle between fixed and fluid layout -->
        <link href="<?php echo template_url(); ?>css/mini.php?files=reset,common,form,standard,960.gs.fluid,simple-lists,block-lists,special-pages,style" rel="stylesheet" type="text/css">
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo template_url(); ?>favicon.ico">

        <!-- Combined JS load -->
        <!-- html5.js has to be loaded before anything else -->
        <script type="text/javascript" src="<?php echo base_url() ?>assets/bundles/jquery-1.7.2.min.js"></script>
         <!--[if lte IE 8]><script type="text/javascript" src="<?php echo template_url(); ?>js/standard.ie.js"></script><![endif]-->
    </head>

    <body class="special-page login-bg dark">
        <!-- The template uses conditional comments to add wrappers div for ie8 and ie7 - just add .ie or .ie7 prefix to your css selectors when needed -->
        <!--[if lt IE 9]><div class="ie"><![endif]-->
        <!--[if lt IE 8]><div class="ie7"><![endif]-->
        <!-- Content -->
        <?php echo $content ?>

        <!-- End content -->

        <!--[if lt IE 8]></div><![endif]-->
        <!--[if lt IE 9]></div><![endif]-->
    </body>
</html>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><!DOCTYPE html>
<!--[if IE 8]> <html lang="zh-CN" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="zh-CN" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="zh-CN">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title><?php if($title){ ?><?php echo $title;?> - <?php } ?><?php echo $config['title'];?><?php if($seo['title']){ ?> - <?php echo $seo['title'];?><?php } ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="dsaiyin" name="author" />
    <base href="<?php echo $sys['url'];?>" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="<?php echo $sys['url'];?>bootstrap/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $sys['url'];?>bootstrap/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $sys['url'];?>bootstrap/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $sys['url'];?>bootstrap/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="<?php echo $sys['url'];?>bootstrap/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="<?php echo $sys['url'];?>bootstrap/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="<?php echo $sys['url'];?>bootstrap/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $sys['url'];?>bootstrap/layouts/layout/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="<?php echo $sys['url'];?>bootstrap/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />
    <link href="<?php echo $sys['url'];?>css/artdialog.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo dsy_url(array('ctrl'=>'js','ext'=>'jquery.artdialog.js'));?>"></script>
    <script type="text/javascript" src="tpl/www/js/global.js" charset="utf-8"></script>
    <?php echo $GLOBALS["app"]->plugin_html_ap("head");?>
<?php echo $app->plugin_html_ap("dsyhead");?></head>
<!-- END HEAD -->
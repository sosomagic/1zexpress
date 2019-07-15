<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><!DOCTYPE html>
<!--[if IE 8]> <html lang="zh-CN" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="zh-CN" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="zh-CN">
<!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <title><?php if($config['title']){ ?><?php echo $config['title'];?>_<?php } ?>后台管理</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="dsaiyin" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="<?php echo $sys['url'];?>bootstrap/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $sys['url'];?>bootstrap/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $sys['url'];?>bootstrap/global/plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $sys['url'];?>bootstrap/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="<?php echo $sys['url'];?>bootstrap/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="<?php echo $sys['url'];?>bootstrap/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="<?php echo $sys['url'];?>bootstrap/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $sys['url'];?>bootstrap/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="<?php echo $sys['url'];?>bootstrap/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link href="css/artdialog.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="favicon.ico" />
    <script type="text/javascript" src="<?php echo dsy_url(array('ctrl'=>'js'));?>"></script>
    <script type="text/javascript" src="<?php echo include_js('admin.index.js');?>"></script>
    <?php echo dsy_head_css();?>
    <?php echo dsy_head_js();?>
    <?php echo $GLOBALS["app"]->plugin_html_ap("head");?>
<?php echo $app->plugin_html_ap("dsyhead");?></head>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white">
<div class="page-wrapper">
    <?php $this->output("nav","file"); ?>
    <div class="page-container">
        <?php $this->output("left","file"); ?>
        <div class="page-content-wrapper">
            <div class="page-content">
                <iframe id="myiframe" src="<?php echo dsy_url(array('ctrl'=>'index','func'=>'main'));?>" frameborder="0" scrolling="no" style="width:100%;" name="main" onload="this.height=200"></iframe>
            </div>
        </div>
    </div>
    <div class="page-footer">
        <div class="page-footer-inner pull-right"> 2016-2019 &copy; Powered By
            <a target="_blank" href="http://www.dsaiyin.com">DSYEX</a>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>
</div>
<script type="text/javascript">
    function changeIframeHeight(){
        var iframe = document.getElementById("myiframe");
        try{
            var bHeight = iframe.contentWindow.document.body.scrollHeight;
            var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
            var height = Math.max(bHeight, dHeight);
            iframe.height = height;
            console.log(height);
        }catch (ex){}
    }
    window.setInterval("changeIframeHeight()", 100);
</script>
<!--[if lt IE 9]>
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/respond.min.js"></script>
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/excanvas.min.js"></script>
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/ie8.fix.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="<?php echo $sys['url'];?>bootstrap/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo $sys['url'];?>bootstrap/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<?php echo $app->plugin_html_ap("dsybody");?></body>
</html>
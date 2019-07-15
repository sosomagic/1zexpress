<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><div class="page-footer">
    <div class="page-footer-inner pull-right"><?php echo $config['copyright']['content'];?></div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
</div>
<!--[if lt IE 9]>
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/respond.min.js"></script>
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/excanvas.min.js"></script>
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/ie8.fix.min.js"></script>
<![endif]-->
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo $sys['url'];?>bootstrap/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="<?php echo $sys['url'];?>bootstrap/global/scripts/app.min.js" type="text/javascript"></script>
<script src="<?php echo $sys['url'];?>bootstrap/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<?php echo $app->plugin_html_ap("dsybody");?></body>
</html>
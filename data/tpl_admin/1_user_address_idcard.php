<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<div class="table">
    <img src="<?php echo $rs['gd']['thumb']['filename'];?>">
</div>
<?php echo $app->plugin_html_ap("dsybody");?></body>
</html>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="author" content="dsayin.com" />
	<meta http-equiv="expires" content="wed, 26 feb 1997 08:21:57 gmt"> 
	<title></title>
	<link href="css/css.php?file=open.css,artdialog.css,swfupload.css,form.css,smartmenu.css" rel="stylesheet" type="text/css" />
	<?php echo dsy_head_css();?>
	<script type="text/javascript" src="<?php echo dsy_url(array('ctrl'=>'js','ext'=>'jquery.artdialog.js'));?>"></script>
	<?php echo dsy_head_js();?>
	<?php echo $GLOBALS["app"]->plugin_html_ap("head");?>
<?php echo $app->plugin_html_ap("dsyhead");?></head>
<body>
<?php if($ispic){ ?>
<style type="text/css">
.img{max-width:650px}
</style>
<div style="margin-top:3px;"><img src="<?php echo $rs['filename'];?>" onload="javascript:if(this.width>650)this.width=650;"></div>
<?php } else { ?>
<div class="list">
<table>
<tr>
	<td>附件名称：</td>
	<td><?php echo $rs['title'];?></td>
</tr>
<tr>
	<td>上传时间：</td>
	<td><?php echo date('Y-m-d H:i:s',$rs['addtime']);?></td>
</tr>
<tr>
	<td>存储目录：</td>
	<td><?php echo $rs['folder'];?></td>
</tr>
<tr>
	<td>文件名：</td>
	<td><?php echo $rs['name'];?></td>
</tr>
<tr>
	<td>下载次数：</td>
	<td><?php echo $rs['download'];?></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="button" value=" 点这里下载 " onclick="window.open('<?php echo $rs['filename'];?>')" /></td>
</tr>
</table>
</div>
<?php } ?>

<?php echo $app->plugin_html_ap("dsybody");?></body>
</html>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><script type="text/javascript">
$(document).ready(function(){
	$("#_dsy_button_url_<?php echo $_rs['identifier'];?>").click(function(){
		var url = get_url("open","url") + "&id=<?php echo $_rs['identifier'];?>";
		$.dialog.open(url,{
			title: "网址管理器",
			lock : true,
			width: "700px",
			height: "70%",
			resize: false
		});
	});
});
</script>
<table cellpadding="0" cellspacing="0" style="width:auto;height:auto;border:0" border="0">
<tr>
	<td>
		<table>
		<tr>
			<td align="right" style="height:40px;">动态页：</td>
			<td><input type="text" id="<?php echo $_rs['identifier'];?>_default" name="<?php echo $_rs['identifier'];?>[default]" style="<?php echo $_rs['form_style'];?>;width:<?php echo $_rs['width'];?>px;" value="<?php echo $_rs['content']['default'];?>" /></td>
		</tr>
		<tr>
			<td align="right" style="height:40px;">伪静态页：</td>
			<td><input type="text" id="<?php echo $_rs['identifier'];?>_rewrite" name="<?php echo $_rs['identifier'];?>[rewrite]" style="<?php echo $_rs['form_style'];?>;width:<?php echo $_rs['width'];?>px;" value="<?php echo $_rs['content']['rewrite'];?>" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="button" value="首页" class="btn btn-xs btn-success" onclick="$('#<?php echo $_rs['identifier'];?>_default').val('index.php');$('#<?php echo $_rs['identifier'];?>_rewrite').val('index.html');" />
				<input type="button" value="网址库" class="btn btn-xs btn-warning" id="_dsy_button_url_<?php echo $_rs['identifier'];?>" />
				<input type="button" value="清除" class="btn btn-xs btn-danger" onclick="$('#<?php echo $_rs['identifier'];?>_default').val('');$('#<?php echo $_rs['identifier'];?>_rewrite').val('');" />
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

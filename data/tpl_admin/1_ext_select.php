<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><select id="_tmp_select_add" style="height: 30px;">
	<option value="">请选择扩展字段…</option>
	<?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
	<option value="<?php echo $value['id'];?>"><?php echo $value['title'];?> - <?php echo $value['identifier'];?></option>
	<?php } ?>
</select>
<input type="button" value="添加" onclick="_update_select_add()" class="btn green-meadow" />
<script type="text/javascript">
function _update_select_add()
{
	var val = $("#_tmp_select_add").val();
	if(!val)
	{
		$.dialog.alert('请选择要添加的扩展');
		return false;
	}
	ext_add2(val,'<?php echo $module;?>');
}
</script>

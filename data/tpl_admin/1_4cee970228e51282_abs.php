<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><input type="hidden" name="<?php echo $_rs['identifier'];?>" id="<?php echo $_rs['identifier'];?>" value="<?php echo $_rs['content'];?>" />
<input type="hidden" id="<?php echo $_rs['identifier'];?>_status" value="" />
<style type="text/css">
.<?php echo $_rs['identifier'];?>_thumb{float:left;width:144px;margin:3px 5px;padding:1px;border:1px solid #ccc;border-radius:3px;position:relative;}
.<?php echo $_rs['identifier'];?>_thumb .sort{position:absolute;right:5px;top:5px;}
.<?php echo $_rs['identifier'];?>_thumb .sort input.taxis{width:40px;border:1px solid #ccc;border-radius:3px;height:22px;text-align:center;padding:3px;}
</style>
<div class="_e_upload">
	<div class="_select">
		<table>
		<tr>
			<?php if($_catelist){ ?>
			<td valign="top">
				<select id="<?php echo $_rs['identifier'];?>_cateid" name="<?php echo $_rs['identifier'];?>_cateid" class="webuploader-select">
				<?php $tmpid["num"] = 0;$_catelist=is_array($_catelist) ? $_catelist : array();$tmpid["total"] = count($_catelist);$tmpid["index"] = -1;foreach($_catelist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
				<option value="<?php echo $value['id'];?>"<?php if($value['id'] == $_rs['cate_id']){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
				<?php } ?>
				</select>
			</td>
			<td valign="top"><button onclick="$.dsyform.upload_cate_create('<?php echo $_rs['identifier'];?>','<?php echo $_rs['upload_type']['title'];?>','<?php echo $_rs['upload_type']['ext'];?>')" class="button" type="button" title="<?php echo P_Lang("添加新分类");?>">+</button></td>
			<?php } ?>
			<td valign="top"><div id="<?php echo $_rs['identifier'];?>_picker"></div></td>
			<td valign="top"><button id="select_res_<?php echo $_rs['identifier'];?>" class="button" type="button"><?php echo P_Lang("选择");?><?php echo $_rs['upload_type']['title'];?></button></td>
			<td valign="top" id="<?php echo $_rs['identifier'];?>_sort" style="display:none">
				<button onclick="obj_<?php echo $_rs['identifier'];?>.sort()" class="button" type="button"><?php echo P_Lang("排序");?></button>
				<button onclick="obj_<?php echo $_rs['identifier'];?>.sort('title')" class="button" type="button"><?php echo P_Lang("自然排序");?></button>
			</td>
			<td valign="top"><div class="gray i" style="line-height:180%;"><?php echo P_Lang("支持格式有：");?><?php echo $_rs['upload_type']['swfupload'];?></div></td>
		</tr>
		</table>
	</div>
	<div class="_progress" id="<?php echo $_rs['identifier'];?>_progress"></div>
	<div class="_list" id="<?php echo $_rs['identifier'];?>_list"></div>
	<div class="clear"></div>
</div>
<script type="text/javascript">
var obj_<?php echo $_rs['identifier'];?>;
$(document).ready(function(){
	//清空本地存储，防止异常删除
	$.dialog.data('upload-<?php echo $_rs['identifier'];?>','');
	obj_<?php echo $_rs['identifier'];?> = new $.admin_upload({
		'id':'<?php echo $_rs['identifier'];?>',
		'swf':'js/webuploader/uploader.swf',
		'server':'<?php echo $sys['url'];?><?php echo dsy_url(array('ctrl'=>'upload','func'=>'save'));?>',
		'cateid':'<?php echo $_rs['cate_id'];?>',
		'pick':{'id':'#<?php echo $_rs['identifier'];?>_picker','multiple':<?php echo $_rs['is_multiple'] ? 'true' : 'false';?>,'innerHTML':'<?php echo P_Lang("选择本地文件");?>'},
		'resize':false,
		'multiple':"<?php echo $_rs['is_multiple'] ? 'true' : 'false';?>",
		"formData":{'<?php echo session_name();?>':'<?php echo session_id();?>'},
		'fileVal':'upfile',
		'disableGlobalDnd':true,
		'compress':<?php echo $_rs['upload_compress'];?>,
		'auto':true,
		'sendAsBinary':<?php echo $_rs['upload_binary'];?>,
		'accept':{'title':'<?php echo $_rs['upload_type']['title'];?>(<?php echo $_rs['upload_type']['swfupload'];?>)','extensions':'<?php echo $_rs['upload_type']['ext'];?>'},
		'fileSingleSizeLimit':'<?php echo $_rs['upload_type']['maxsize']*100;?>'
	});
	obj_<?php echo $_rs['identifier'];?>.showhtml();
	$("#select_res_<?php echo $_rs['identifier'];?>").click(function(){
		var url = "<?php echo dsy_url(array('ctrl'=>'open','func'=>'upload','id'=>$_rs['identifier'],'multiple'=>$_rs['is_multiple'],'cate_id'=>$_rs['upload_type']['id']));?>";
		var t = "<?php echo $_rs['is_multiple'] ? 'true' : 'false';?>";
		var old = $("#<?php echo $_rs['identifier'];?>").val();
		var doc_width = $(document).width();
		if(doc_width < 1024){
			var width = '645px';
			var height = '450px';
		}else{
			var width = '64%';
			var height = '80%';
		}
		if(t == 'true'){
			if(old){
				$.dialog.data('select-<?php echo $_rs['identifier'];?>',old);
			}
			
			$.dialog.open(url,{
				'title': '<?php echo P_Lang("资源管理器");?>',
				'lock' : true,
				'width': width,
				'height': height,
				'ok': function(){
					var val = $.dialog.data('select-<?php echo $_rs['identifier'];?>');
					if(val){
						$("#<?php echo $_rs['identifier'];?>").val(val);
					}else{
						$("#<?php echo $_rs['identifier'];?>").val('');
					}
					$.dialog.data('select-<?php echo $_rs['identifier'];?>','');
					obj_<?php echo $_rs['identifier'];?>.showhtml();
				},
				'cancel':true,
				'cancelVal':'<?php echo P_Lang("取消");?>'
			});
		}else{
			if(old){
				url += "&selected="+old;
			}
			$.dialog.open(url,{
				title: "<?php echo P_Lang("资源管理器");?>",
				lock : true,
				'width': width,
				'height': height
			});
		}
	});
});
</script>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="author" content="dsaiyin.com" />
<meta http-equiv="expires" content="wed, 26 feb 1997 08:21:57 gmt"> 
<title>资源管理器</title>
<style type="text/css">
html{font-size:14px;font-family:"Microsoft Yahei","宋体","Arial","Tahoma";margin:0;padding:0;background:#FFFFFF;}
body{margin:3px;padding:0;overflow-y:scroll;_margin:0; _height:100%;}
a{color:#000;text-decoration: none;}
ul.filelist{list-style:none;padding:0;margin:0}
ul.filelist li{float:left;margin:3px 5px;height:75px;width:296px;padding:1px;border:1px solid #ccc;cursor:pointer;}
ul.filelist li:hover{background:#efefef;}
ul.filelist li .ico{float:left;width:75px;height:75px;text-align:center;position:relative;z-index: 2;}
ul.filelist li .ico .checkbox{position:absolute;width:20px;height:20px;background:#fff;top:1px;left:1px;z-index:1;}
ul.filelist li .note{float:left;width:220px;overflow:hidden;}
ul.filelist li .note .info{line-height:22px;overflow:hidden;height:22px;}
ul.filelist li img.img{width:73px;height:73px;text-align:center;padding:1px;}

.pagelist{text-align:center;height:30px;overflow:hidden;margin-top:7px;}
.pagelist ul{list-style:none;margin:0;padding:0;text-align:center;}
.pagelist ul li{display:inline;height:22px;line-height:24px;margin:0 5px 0 0;}
.pagelist ul li a{display:inline;padding:1px 5px;border:1px solid #ddd;cursor:pointer;}
.pagelist ul li a:hover{background:#efefef;border:1px solid #ccc;text-decoration:none;}
.pagelist ul li a.current{background:#E4E4E4;border:1px solid #ccc;text-decoration:none;}
div.clear{clear:both;height:0;line-height:0;overflow:hidden;display:block;visibility:hidden;}
.search{border:1px solid #E5E5E5;background:#F5F5F5;padding-left:12px;line-height:25px;margin-top:3px;}
select{padding:3px;}
input.keywords{width:150px;padding:3px;border:1px solid #ABADB3;}
</style>
<link rel="stylesheet" type="text/css" href="css/artdialog.css" />
<link rel="stylesheet" type="text/css" href="css/form.css" />
<?php echo dsy_head_css();?>
<script type="text/javascript" src="<?php echo dsy_url(array('ctrl'=>'js','ext'=>'jquery.artdialog.js'));?>"></script>
<script type="text/javascript" src="js/laydate/laydate.js"></script>
<?php echo dsy_head_js();?>
<script type="text/javascript">
function dsy_input(val)
{
	$(".piclist li").removeClass("hover");
	var obj = $.dialog.opener;
	obj.obj_<?php echo $id;?>.open_action(val);
	var content = obj.$("#<?php echo $id;?>").val();
	if(content)
	{
		var list = content.split(",");
		for(var i in list)
		{
			$("#attr_"+list[i]).addClass("hover");
		}
	}
	if(is_more == false){
		$.dialog.close();
	}
}
function update_select(val)
{
	if(val == 'start_date' || val == 'stop_date'){
		$("#keywords").focus(function(){
			laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})
		});
	} else {
		$("#keywords").unbind('focus').click(function(){
			$(this).select();
		});
	}
}
function check_search()
{
	var cate_id = $("#top_cate_id").val();
	var keywords = $("#keywords").val();
	if(!cate_id && !keywords){
		$.dialog.alert('请选择要搜索的项');
		return false;
	}
	return true;
}
function add_input(id)
{
	var obj = $.dialog.opener;
	obj.$("#<?php echo $id;?>").val(id);
	obj.obj_<?php echo $id;?>.showhtml();
	$.dialog.close();
}
function add_select(id){
	var dval = $.dialog.data('select-<?php echo $id;?>');
	if(dval && id == dval){
		//删除操作
		$.dialog.data('select-<?php echo $id;?>','');
		$("#addfile_"+id).attr('checked',false);
		return true;
	}
	if(!dval || dval == 'undefined'){
		$.dialog.data('select-<?php echo $id;?>',id);
		$("#addfile_"+id).attr('checked',true);
		return true;
	}
	var checked = $("#addfile_"+id).is(':checked');
	if(checked){
		//删除
		var list = dval.split(',');
		var newlist = new Array();
		var new_i = 0;
		for(var i in list){
			if(list[i] != id){
				newlist[new_i] = list[i];
				new_i++;
			}
		}
		$("#addfile_"+id).attr("checked",false);
		tmp = newlist.join(',');
		$.dialog.data('select-<?php echo $id;?>',tmp);
		return true;
	}
	if(dval){
		dval += ","+id;
	}else{
		dval = id;
	}
	var lst = $.unique(dval.split(","));
	dval = lst.join(',');
	$("#addfile_"+id).attr('checked',true);
	$.dialog.data('select-<?php echo $id;?>',dval);
	return true;
}
$(document).ready(function(){  
	$(document).bind("contextmenu",function(e){
		return false;   
	});
});
</script>
<?php echo $app->plugin_html_ap("dsyhead");?></head>
<body>
<div class="search">
	<form method="post" action="<?php echo $pageurl;?>" onsubmit="return check_search()">
	<table>
	<tr>
		<td>搜索：</td>
		<td><select name="cate_id" id="top_cate_id">
			<option value="">全部分类…</option>
			<?php $tmpid["num"] = 0;$catelist=is_array($catelist) ? $catelist : array();$tmpid["total"] = count($catelist);$tmpid["index"] = -1;foreach($catelist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
			<option value="<?php echo $value['id'];?>"<?php if($value['id'] == $cate_id){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
			<?php } ?>
		</select></td>
		<td><select name="keytype" id="keytype" onchange="update_select(this.value)">
			<?php $tmpid["num"] = 0;$keytype_list=is_array($keytype_list) ? $keytype_list : array();$tmpid["total"] = count($keytype_list);$tmpid["index"] = -1;foreach($keytype_list AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
			<option value="<?php echo $key;?>"<?php if($key == $keytype){ ?> selected<?php } ?>><?php echo $value;?></option>
			<?php } ?>
		</select></td>
		<td><input type="text" name="keywords" id="keywords" value="<?php echo $keywords;?>" class="keywords" /></td>
		<td><input type="submit" value=" <?php echo P_Lang("搜索");?> " /></td>
		<td><input type="button" value=" <?php echo P_Lang("刷新");?> " onclick="$.dsy.reload()" /></td>
	</tr>
	</table>
	</form>
</div>
<ul class="filelist">
	<?php $tmpid["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$tmpid["total"] = count($rslist);$tmpid["index"] = -1;foreach($rslist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
	<li<?php if($multiple){ ?> onclick="add_select('<?php echo $value['id'];?>')" id="li_<?php echo $value['id'];?>"<?php } else { ?> onclick="add_input('<?php echo $value['id'];?>')"<?php } ?>>
		<div class="li_bg">
		<div class="ico">
			<img src="<?php echo $value['ico'];?>" class="img" />
			<?php if($multiple){ ?>
			<div class="checkbox"><input type="checkbox" name="addfile[]" id="addfile_<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" /></div>
			<?php } ?>
		</div>
		<div class="note">
			<div class="info">名称：<?php echo $value['title'];?></div>
			<div class="info">添加：<?php echo date('Y-m-d H:i:s',$value['addtime']);?></div>
			<div class="info">属性：<?php echo $value['attr'] ? $value['attr']['width'].' x '.$value['attr']['height'] : '-';?></div>
		</div>
		<div class="clear"></div>
		</div>
	</li>
	<?php } ?>
	<div class="clear"></div>
</ul>
<?php if($pagelist){ ?>
<div class="pagelist">
<ul>
	<?php $tmpid["num"] = 0;$pagelist=is_array($pagelist) ? $pagelist : array();$tmpid["total"] = count($pagelist);$tmpid["index"] = -1;foreach($pagelist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
	<li><a href="<?php echo $value['url'];?>"<?php if($value['status']){ ?> class="current"<?php } ?>><?php echo $value['title'];?></a></li>
	<?php } ?>
</ul>
</div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function(){
	var old = $.dialog.data('select-<?php echo $id;?>');
	if(old && old != 'undefined'){
		var list = old.split(',');
		for(var i in list){
			$("#addfile_"+list[i]).attr('checked',true);
		}
	}
});
</script>
<?php if($session['admin_id'] && $sys['app_id'] == 'admin'){ ?>
<style type="text/css">
.table{padding:2px 10px 2px 0;margin:1px 5px 0 0;cursor:default;}
.table:after{clear:both;height:0;visibility:hidden;display: block;}
.table .title{padding-left:3px;line-height:23px;}
.table .title span.note{color:gray;font-style:italic;font-weight:normal;}
.table .content{padding-left:3px;}
.table .content .price{margin-top:5px;margin-right:0;}
.table .content .price th{padding:0;border-bottom:2px solid #E5E5E5;height:25px;background:#D1D1D1;}
.table .content .price th.lft{text-align:left;}
.table .content .price tr.list{background:#F3F3F3}
.table .content .price td{padding:3px;}
.table .content textarea{font-size:14px;font-family:"Microsoft Yahei","宋体","Arial","Tahoma"}
.table .content td label{margin:0 10px 0 0;padding:0;display:inline-block;}
.table .content td label input{margin-right:5px;vertical-align:-7%;}
.table .content ul.lang{margin:0;padding:0;list-style:none;}
.table .content ul.lang li{margin-bottom:3px;}
</style>
<link rel="stylesheet" type="text/css" href="js/webuploader/webuploader.css" />
<script type="text/javascript" src="js/webuploader/webuploader.min.js"></script>
<script type="text/javascript" src="js/webuploader/admin.upload.js"></script>
<script type="text/javascript">
var obj_upload = {};
var obj = art.dialog.opener;
$(document).ready(function(){
	cate_change();
});
function cate_change()
{
	val = $("#upload_cate_id").val();
	if(!val){
		$.dialog.alert('请选择要存储的目标分类');
		return false;
	}
	var data = $("#upload_cate_id option[value="+val+"]").attr('data');
	var catename = $("#upload_cate_id option[value="+val+"]").attr('catename');
	obj_upload = new $.admin_upload({
		"multiple"	: 'true',
		"id" : "upload",
		'pick':{'id':'#upload_picker','multiple':true},
		'resize':false,
		"swf" : "js/webuploader/uploader.swf",
		"server": "<?php echo dsy_url(array('ctrl'=>'upload','func'=>'save'));?>",
		'accept' : {'title':catename,'extensions':data},
		"formData" :{'<?php echo session_name();?>':'<?php echo session_id();?>','cateid':val},
		'fileVal':'upfile',
		'sendAsBinary':<?php echo $sendAsBinary ? 'true' : 'false';?>,
		'auto':true,
		"success":function(){
			return true;
		}
	});
	obj_upload.uploader.on('uploadFinished',function(){
		$.dialog.alert('附件上传成功',function(){
			$.dsy.reload();
		});
	});
}
function cancel()
{
	return obj_upload.uploader.stop();
}
</script>
<hr />
<div class="table">
	<div class="title">
		<?php echo P_Lang("附件分类：");?>
		<span class="note"><?php echo P_Lang("请选择要上传的附件分类");?></span>
	</div>
	<div class="content">
		<select id="upload_cate_id" onchange="cate_change()">
			<?php $catelist_id["num"] = 0;$catelist=is_array($catelist) ? $catelist : array();$catelist_id["total"] = count($catelist);$catelist_id["index"] = -1;foreach($catelist AS $key=>$value){ $catelist_id["num"]++;$catelist_id["index"]++; ?>
			<option value="<?php echo $value['id'];?>"<?php if($value['id'] == $cate_id){ ?> selected<?php } ?> data="<?php echo $value['filetypes'];?>" catename="<?php echo $value['title'];?>">
			<?php echo $value['title'];?><?php if($value['typeinfos']){ ?> / <?php echo P_Lang("支持上传格式：");?><?php echo $value['typeinfos'];?><?php } ?></option>
			<?php } ?>
		</select>
	</div>
</div>
<div class="table">
	<div class="title">
		<?php echo P_Lang("选择要上传的文件：");?>
		<span class="note"><?php echo P_Lang("单个文件上传不能超过：");?><span class="red"><?php echo get_cfg_var('upload_max_filesize');?></span></span>
	</div>
	<div class="content"><div id="upload_picker" class=""></div></div>
</div>

<div class="table">
	<div class="content" id="upload_progress"></div>
</div>
<?php } ?>
<?php echo $app->plugin_html_ap("dsybody");?></body>
</html>
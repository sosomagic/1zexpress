<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><div id="<?php echo $_rs['identifier'];?>_html"></div>
<select name="<?php echo $_rs['identifier'];?>_p" id="<?php echo $_rs['identifier'];?>_p" onchange="read_city_<?php echo $_rs['identifier'];?>()">
	<option value="" province_id=''>请选择…</option>
	<?php $tmpid["num"] = 0;$_province=is_array($_province) ? $_province : array();$tmpid["total"] = count($_province);$tmpid["index"] = -1;foreach($_province AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
	<option value="<?php echo $value['val'];?>"<?php if($_rs['content']['p'] == $value['val']){ ?> selected<?php } ?> province_id="<?php echo $value['attr']['id'];?>"><?php echo $value['val'];?></option>
	<?php } ?>
</select>
<select name="<?php echo $_rs['identifier'];?>_c" id="<?php echo $_rs['identifier'];?>_c" onchange="read_county_<?php echo $_rs['identifier'];?>()" style="display:none" data="<?php echo $_rs['content']['c'];?>">
	<option value="" city_id=''>请选择…</option>
</select>
<!--<select name="<?php echo $_rs['identifier'];?>_a" id="<?php echo $_rs['identifier'];?>_a" style="display:none" data="<?php echo $_rs['content']['a'];?>">
	<option value="" area_id=''>请选择…</option>
</select>-->
<script type="text/javascript">
var form_pca_<?php echo $_rs['identifier'];?>_p = '<?php echo $rs['content'] ? $rs['content']['p'] : "";?>';
var form_pca_<?php echo $_rs['identifier'];?>_c = '<?php echo $rs['content'] ? $rs['content']['c'] : "";?>';
//var form_pca_<?php echo $_rs['identifier'];?>_a = '<?php echo $rs['content'] ? $rs['content']['a'] : "";?>';
function read_city_<?php echo $_rs['identifier'];?>()
{
	var province_id = $("#<?php echo $_rs['identifier'];?>_p").find("option:selected").attr('province_id');
	if(!province_id){
		$("#<?php echo $_rs['identifier'];?>_c").hide();
		//$("#<?php echo $_rs['identifier'];?>_a").hide();
		return true;
	}else{
		//读城市Ajax
		var url = get_url('inp','xml','file=cities');
		var city = $("#<?php echo $_rs['identifier'];?>_c").attr('data');
		$.dsy.json(url,function(data){
			if(data.status != 'ok'){
				alert('内容获取异常：'+data.content);
				return false;
			}
			var info = data.content.city;
			var html = '';
			for(var i in info){
				if(info[i].attr.pid == province_id){
					html += '<option value="'+info[i].val+'" city_id="'+info[i].attr.id+'" zip="'+info[i].attr.zipcode+'"';
					if((city && info[i].val == city) || (form_pca_<?php echo $_rs['identifier'];?>_c && form_pca_<?php echo $_rs['identifier'];?>_c == info[i].val)){
						html += ' selected';
					}
					html += '>'+info[i].val+'</option>';
				}
			}
			$("#<?php echo $_rs['identifier'];?>_c").html(html).show();
			//$("#<?php echo $_rs['identifier'];?>_a").hide();
			//read_county_<?php echo $_rs['identifier'];?>();
			read_county_pca();
		});
	}
}
/*function read_county_<?php echo $_rs['identifier'];?>()
{
	var city_id = $("#<?php echo $_rs['identifier'];?>_c").find("option:selected").attr('city_id');
	if(!city_id){
		$("#<?php echo $_rs['identifier'];?>_a").hide();
		return true;
	}else{
		var url = get_url('inp','xml','file=districts');
		var area = $("#<?php echo $_rs['identifier'];?>_a").attr('data');
		$.dsy.json(url,function(data){
			if(data.status != 'ok'){
				alert('内容获取异常：'+data.content);
				return false;
			}
			var info = data.content.district;
			var html = '';
			for(var i in info){
				if(info[i].attr.cid == city_id){
					html += '<option value="'+info[i].val+'"';
					if((area && info[i].val == area) || (form_pca_<?php echo $_rs['identifier'];?>_a && form_pca_<?php echo $_rs['identifier'];?>_a == info[i].val)){
						html += ' selected';
					}
					html += '>'+info[i].val+'</option>';
				}
			}
			if(html){
				$("#<?php echo $_rs['identifier'];?>_a").html(html).show();
			}else{
				$("#<?php echo $_rs['identifier'];?>_a").hide();
			}
			
		});
	}
}*/
$(document).ready(function(){
	read_city_<?php echo $_rs['identifier'];?>();
});
</script>
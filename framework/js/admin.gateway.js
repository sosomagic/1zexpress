/**
 * 用于后台的网关路由涉及到的JS
**/
function add_it(id)
{
	var url = get_url('gateway','getlist','id='+id);
	var rs = $.dsy.json(url);
	if(rs.status == 'ok'){
		var content = '<select id="code">';
		for(var i in rs.content){
			content += '<option value="'+rs.content[i].id+'">'+rs.content[i].title;
			if(rs.content[i].note){
				content += ' / '+rs.content[i].note+'';
			}
			content += '</option>';
		}
		content += '</select>';
		$.dialog({
			'title': '网关选择器',
			'lock':true,
			'content':content,
			'cancel':function(){return true;},
			'cancelVal':'取消',
			'okVal':'提交',
			'ok':function(){
				var code = $("#code").val();
				var url = get_url('gateway','set','type='+id+"&code="+code);
				$.dsy.go(url);
				return true;
			}
		});
	}else{
		$.dialog.alert(rs.content);
		return false;
	}
}
function update_taxis(val,id)
{
	$.ajax({
		'url':get_url('gateway','sort','sort['+id+']='+val),
		'dataType':'json',
		'cache':false,
		'async':true,
		'beforeSend': function (XMLHttpRequest){
			XMLHttpRequest.setRequestHeader("request_type","ajax");
		},
		'success':function(rs){
			if(rs.status == 'ok'){
				$.dsy.reload();
			}else{
				$.dialog.alert(rs.content);
				return false;
			}
		}
	});
}
function update_status(id,val)
{
	if(val == 1){
		$.dialog.confirm('确定要关闭这个网关吗？',function(){
			var url = get_url('gateway','status','id='+id+"&status=0");
			var rs = $.dsy.json(url);
			if(rs && rs.status == 'ok'){
				$.dsy.reload();
			}else{
				$.dialog.alert(rs.content);
				return false;
			}
		});
	}else{
		var url = get_url('gateway','status','id='+id+"&status=1");
		var rs = $.dsy.json(url);
		if(rs && rs.status == 'ok'){
			$.dsy.reload();
		}else{
			$.dialog.alert(rs.content);
			return false;
		}
	}
}
function update_default(id)
{
	var url = get_url('gateway','default','id='+id);
	var rs = $.dsy.json(url);
	if(rs.status == 'ok'){
		$.dsy.reload();
	}else{
		$.dialog.alert(rs.content);
		return false;
	}
}
function delete_it(id,title)
{
	$.dialog.confirm('确定要删除网关：<span class="red">'+title+"</span> 吗？删除后是不能恢复的",function(){
		var url = get_url('gateway','delete','id='+id);
		var rs = $.dsy.json(url);
		if(rs.status == 'ok'){
			$.dsy.reload();
		}else{
			$.dialog.alert(rs.content);
			return false;
		}
	});
}

function gateway_extmanage(id,manageid,type)
{
	var url = get_url('gateway','extmanage','id='+id+"&manageid="+manageid);
	if(type == 'ajax'){
		var rs = $.dsy.json(url);
		if(rs.status){
			$.dialog.alert(rs.info,function(){
				return true
			},'succeed');
		}else{
			$.dialog.alert(rs.info);
		}
	}else{
		$.dialog.open(url,{
			'title':'网关路由管理 #'+id,
			'lock':true,
			'width':'680px',
			'height':'500px'
		});
	}
}

$(document).ready(function(){
	$("div[name=taxis]").click(function(){
		var oldval = $(this).text();
		var id = $(this).attr('data');
		$.dialog.prompt('请填写新的排序',function(val){
			if(val != oldval){
				update_taxis(val,id);
			}
		},oldval);
	});
});
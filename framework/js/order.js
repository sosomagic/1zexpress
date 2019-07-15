//添加一行
function add_row()
{
    var cate_option = $("#pro_cate").val();
    var unit_option = $("#pro_unit").val();
    //var currency_option = $("#pro_currency").val();
	var total = $("#prolist tr.prolist").length;
	if($("#pro_"+total).length>0) total = total.toString() + "_"+ (parseInt(100*Math.random())).toString();
	var html = '<tr id="pro_'+total+'" class="prolist">';
	html += '<input type="hidden" name="pro_id[]" value="add" />';
    if(cate_option){
        html += '<td><select name="catename[]" class="input-xsmall">'+cate_option+'</select></td>';
    }
    html += '<td><input type="text" name="brand[]" value="" required="" class="input-small" /></td>'
    html += '<td><input id="pro_title_'+total+'" type="text" name="title[]" value="" required="" class="input-small" /></td>'
    html += '<td><input type="text" name="size[]" value="" class="input-small"/></td>'
    //html += '<td><select style="width:40px;" name="unit[]">'+unit_option+'</select></td>'
    //html += '<td><input type="text" name="weight[]" class="weight input-xsmall" value="" onkeyup=value=value.replace(/[^\\d.]/g,"") /></td>'
    html += '<td><input type="text" name="qty[]" class="count input-xsmall" value="" onkeyup=value=value.replace(/[^\\d.]/g,"") /></td>'
    html += '<td><input type="text" name="price[]" class="unit_price input-xsmall" value="" onkeyup=value=value.replace(/[^\\d.]/g,"") /></td>'
    html += '<td><input type="text" name="total_price[]" class="total_price input-xsmall" value="" readonly="readonly" /></td>'
    //html += '<td class="center"><select class="qty" name="currency[]">'+currency_option+'</select></td>'
	html += '<td class="text-center"><a onclick="order_pro_delete2(\''+total+'\')" href="javascript:;"><img src="tpl/www/images/del.gif" title="删除申报商品"></a></td>';
	html += '</tr>';
	$("#prolist").append(html);
}

//弹出窗口选取商品
function pro_select(id)
{
	var url = get_url('order','prolist','id='+$.str.encode(id));
	var currency_id = $("#currency_id").val();
	url += '&currency_id='+currency_id;
	var ids='';
	$("input.p_proid").each(function(i){
		var t = $(this).val();
		if(t && t != '0' && t != 'undefined'){
			ids += t+",";
		}
	});
	if(ids){
		ids = ids.substr(0,(ids.length - 1));
		url += "&exinclude="+$.str.encode(ids);
	}
	$.dialog.open(url,{
		'title':'选择商品',
		'width':'70%',
		'height':'70%',
		'lock':true,
		'resize':false,
		'fixed':true
	});
}
/*function package_info(id){
    url = get_url('package','info','id='+id);
    direct(url);
}*/
//会员中心编辑包裹
function package_edit(id)
{
    var url = get_url('package','forecast','id='+id);
    direct(url);
}
//会员中心删除包裹
function package_delete(id,title)
{
    $.dialog.confirm('确定要删除包裹：<span class="red">'+title+'</span> 吗?<br />删除后您不能再恢复，请慎用<br /><span class="darkblue">删除成功后系统会自动刷新当前页面且不提示</span>',function(){
        var url = get_url('package','delete','id='+id);
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $.dsy.reload();
        }
        else
        {
            $.dialog.alert(rs.content);
            return false;
        }
    });
}
//编辑订单
function order_edit(id,pageurl)
{
    var url;
    if(pageurl){
        pageurl = pageurl.replace(/\&/g, "%26");//"&"
        url = get_url('order','set','id='+id+'&pageurl='+pageurl);
    }else{
        url = get_url('order','set','id='+id);
    }
	$.dsy.go(url);
}
//订单扣款
function order_pay(id,pageurl)
{
    var url;
    if(pageurl){
        pageurl = pageurl.replace(/\&/g, "%26");//"&"
        url = get_url('order','pay','id='+id+'&pageurl='+pageurl);
    }else{
        url = get_url('order','pay','id='+id);
    }
    direct(url);
}
function order_pro_delete2(id)
{
	$("#pro_"+id).remove();
}

function remove(id,title)
{
	$.dialog.confirm('确定要删除运单：<span class="font-red">'+title+'</span> 吗?<br /><span class="font-blue">删除成功后系统会自动刷新当前页面且不提示</span>',function(){
		var url = get_url('order','remove','id='+id);
		var rs = json_ajax(url);
		if(rs.status == 'ok')
		{
			$.dsy.reload();
		}
		else
		{
			$.dialog.alert(rs.content);
			return false;
		}
	});
}
//回收站单个删除
function order_delete(id,title)
{
    $.dialog.confirm('确定要删除订单：<span class="font-red">'+title+'</span> 吗?<br />删除后您不能再恢复，请慎用<br /><span class="darkblue">删除成功后系统会自动刷新当前页面且不提示</span>',function(){
        var url = get_url('order','delete','id='+id);
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $.dsy.reload();
        }
        else
        {
            $.dialog.alert(rs.content);
            return false;
        }
    });
}
/*function order_check(id,title)
{
	$.dialog.confirm('确定要审核订单：<span class="red">'+title+'</span> 吗?<br />订单审核成功后，前台将没有删除权限',function(){
		var url = get_url('order','status','id='+id);
		var rs = json_ajax(url);
		if(rs.status == 'ok')
		{
			$.dsy.reload();
		}
		else
		{
			$.dialog.alert(rs.content);
			return false;
		}
	});
}*/

//更新密码
function update_passwd()
{
	var chars = ['0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	var res = '';
	for(var i = 0; i < 10 ; i ++)
	{
		var id = Math.ceil(Math.random()*35);
		res += chars[id];
    }
    $("#passwd").val($.md5(res));
}

function update_sn()
{
	var res = 'DSY';
	var myDate = new Date();
	res += myDate.getFullYear();
	var month = myDate.getMonth() + 1;
    month =(month<10 ? "0"+month:month);
    if(month.length == 1)
	{
		month = '0'+month.toString();
	}
	res += month;
	var date = myDate.getDate();
	if(date.length == 1)
	{
		date = '0'+date.toString();
	}
	res += date;
	var hour = myDate.getHours() + 1;
	if(hour.length == 1)
	{
		hour = '0'+hour.toString();
	}
	res += hour;
	var minutes = myDate.getMinutes();
	if(minutes.length == 1)
	{
		minutes = '0'+minutes.toString();
	}
	res += minutes;
	var seconds = myDate.getSeconds();
	if(seconds.length == 1)
	{
		seconds = '0'+seconds.toString();
	}
	res += seconds;
	var chars = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	for(var i = 0; i < 3 ; i ++)
	{
		var id = Math.ceil(Math.random()*25);
		res += chars[id];
    }
    $("#sn").val(res);
}
//删除包裹产品，删除操作成功会更新订单金额
function package_pro_delete(id,numid)
{
    var title = $("#pro_title_"+numid).val();
    $.dialog.confirm("确定要删除产品：<span class='font-red'>"+title+"</span>",function(){
        var url = get_url('package','product_delete','id='+id);
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $.dialog.alert("产品：<span class='font-red'>"+title+"</span> 已成功删除！",function(){
                //$.dsy.reload();
				$("tr[id='pro_"+numid+"']").remove();//删除当前行
            });
        }
        else
        {
            if(!rs.content) rs.content = '删除失败';
            $.dialog.alert(rs.content);
            return false;
        }
    });
}
//删除订单产品，删除操作成功会更新订单金额
function order_pro_delete(id,numid)
{
	var title = $("#pro_title_"+numid).val();
	$.dialog.confirm("确定要删除产品：<span class='font-red'>"+title+"</span>",function(){
		var url = get_url('order','product_delete','id='+id);
		var rs = json_ajax(url);
		if(rs.status == 'ok')
		{
			$.dialog.alert("产品：<span class='font-red'>"+title+"</span> 已成功删除！",function(){
				//$.dsy.reload();
				$("tr[id='pro_"+numid+"']").remove();//删除当前行
			});
		}
		else
		{
			if(!rs.content) rs.content = '删除失败';
			$.dialog.alert(rs.content);
			return false;
		}
	});
}

//通过Ajax加载产品信息
function load_product(num,id)
{
	var url = get_url('order','product','id='+id);
	var currency_id = $("#currency_id").val();
	url += '&currency_id='+currency_id;
	var rs = $.dsy.json(url);
	if(rs.status != 'ok'){
		if(!rs.content){
			rs.content = '产品信息获取失败';
		}
		$.dialog.alert(rs.content);
		return false;
	}
	$("#pro_tid_"+num).val(id);
	if(rs.content.thumb){
		$("#pro_thumb_"+num).val(rs.content.thumb.filename);
		$("#pro_thumb_view_"+num).html('<img src="'+rs.content.thumb.ico+'" border="0" width="80px" height="80px" onclick="update_pic(\''+num+'\')" style="cursor:pointer;" />');
	}
	$("#pro_title_"+num).val(rs.content.title);
	$("#pro_price_"+num).val(rs.content.price);
	return true;
}

//更新附件
function update_pic(tid)
{
	var url = get_url('order','thumb','id='+tid);
	$.dialog.open(url,{
		'title':'图片库',
		'width':'50%',
		'height':'80%',
		'lock':true,
		'resize':false,
		'fixed':true
	});
}

function such_as_shipping()
{
	$("#b-fullname").val($("#s-fullname").val());
	$("#b-country").val($("#s-country").val());
	$("#b-province").val($("#s-province").val());
	$("#b-city").val($("#s-city").val());
	$("#b-county").val($("#s-county").val());
	$("#b-address").val($("#s-address").val());
	$("#b-zipcode").val($("#s-zipcode").val());
	$("#b-tel").val($("#s-tel").val());
	$("#b-mobile").val($("#s-mobile").val());
	$("#b-email").val($("#s-email").val());
	var gender = $("input[name=s-gender]:checked").val();
	$("input[name=b-gender][value="+gender+"]").attr("checked",true);
}

function get_user_email()
{
	var uid = $("#user_id").val();
	if(!uid){
		$.dialog.alert(p_lang('未绑定会员账号'));
		return false;
	}
	var url = get_url('user','info','uid='+uid);
	$.dsy.json(url,function(rs){
		if(rs.status == 'ok'){
			$("#email").val(rs.content.email);
			return true;
		}else{
			$.dialog.alert(rs.content);
			return false;
		}
	})
}

function get_user_invoice()
{
	var uid = $("#user_id").val();
	if(!uid){
		$.dialog.alert(p_lang('未绑定会员账号'));
		return false;
	}
	var url = get_url('user','info','uid='+uid+"&type=invoice");
	$.dsy.json(url,function(rs){
		if(rs.status == 'ok'){
			var info = rs.content.rs;
			var list = rs.content.rslist;
			invoice_show_select(list,info.id);
			$("#invoice_type").val(info.type);
			$("#invoice_title").val(info.title);
			$("#invoice_content").val(info.content);
			return true;
		}else{
			$.dialog.alert(rs.content);
			return false;
		}
	})
}
function update_user_invoice(obj)
{
	var obj = $(obj).find("option:selected");
	$("#invoice_type").val(obj.attr('type'));
	$("#invoice_title").val(obj.attr('title'));
	$("#invoice_content").val(obj.attr('content'));
}

function invoice_show_select(list,id)
{
	var html = '<select onchange="update_user_invoice(this)">';
	for(var i in list){
		html += '<option value="'+list[i].id+'" type="'+list[i].type+'" title="'+list[i].title+'" content="'+list[i].content+'"';
		if(list[i].id == id){
			html += ' selected';
		}
		html += '>'+list[i].type+'/'+list[i].title+'</option>';
	}
	html += '</select>';
	$("#invoice_user_select").html(html);
}
function update_user_address(obj)
{
	var obj = $(obj).find("option:selected");
	$("#s-fullname").val(obj.attr('fullname'));
	$("#s-country").val(obj.attr('country'));
	$("#s-province").val(obj.attr('province'));
	$("#s-city").val(obj.attr('city'));
	$("#s-county").val(obj.attr('county'));
	$("#s-address").val(obj.attr('address'));
	$("#s-mobile").val(obj.attr('mobile'));
	$("#s-tel").val(obj.attr('tel'));
	$("#s-email").val(obj.attr('email'));
	$("#s-idcard").val(obj.attr('idcard'));
}
function user_show_select(list,id)
{
	var html = '<select onchange="update_user_address(this)">';
	for(var i in list){
		html += '<option value="'+list[i].id+'" fullname="'+list[i].fullname+'" country="'+list[i].country+'" ';
		html += 'city="'+list[i].city+'" province="'+list[i].province+'" county="'+list[i].county+'"';
		html += 'address="'+list[i].address+'" mobile="'+list[i].mobile+'" tel="'+list[i].tel+'" email="'+list[i].email+'" idcard="'+list[i].idcard+'"';
		if(list[i].id == id){
			html += ' selected';
		}
		html += '>'+list[i].fullname+'：'+list[i].province+list[i].city+list[i].county+list[i].address;
		if(list[i].mobile){
			html += '/'+list[i].mobile;
		}
		html += '</option>'
	}
	html += '</select>';
	$("#address_user_select").html(html);
}

function get_user_address()
{
	var uid = $("#user_id").val();
	if(!uid){
		$.dialog.alert(p_lang('未绑定会员账号'));
		return false;
	}
	var url = get_url('user','info','uid='+uid);
	/*$.dsy.json(url,function(rs){
		if(rs.status == 'ok'){
			var info = rs.content.rs;
			var list = rs.content.rslist;
			user_show_select(list,info.id);
			$("#s-fullname").val(info.fullname);
			$("#s-country").val(info.country);
			$("#s-province").val(info.province);
			$("#s-city").val(info.city);
			$("#s-county").val(info.county);
			$("#s-address").val(info.address);
			$("#s-mobile").val(info.mobile);
			$("#s-tel").val(info.tel);
			$("#s-email").val(info.email);
			$("#s-zipcode").val(info.zipcode);
            $("#s-idcard").val(info.idcard);
            $("#idcard_front").val(info.idcard_front);
            $("#idcard_back").val(info.idcard_back);
			return true;
		}else{
			$.dialog.alert(rs.content);
			return false;
		}
	})*/
    $.dialog.open(url,{
        title: "收件人管理",
        lock : true,
        width: "70%",
        height: "100%",
        resize: false
    });
}

function total_price()
{
	var total = 0;
	$("input[sign=ext_price]").each(function(i){
		var val = $(this).val();
		val = parseFloat(val);
		if(isNaN(val)){
			val = 0;
		}
		if($(this).attr("action") == 'add'){
			total += val;
		}else{
			total -= val;
		}
	});
	$('#price,#pay_price').val(total.toString());
}

function order_express(id,sn)
{
	var url = get_url('order','express','id='+id);
	$.dialog.open(url,{
		'title':p_lang('运单：')+'<span class="font-red">'+sn+'</span>'+p_lang('物流快递信息'),
		'width':'70%',
		'height':'70%',
		'lock':true
	});
}
function order_delivery(id,sn)
{
    var url = get_url('order','delivery','id='+id);
    $.dialog.open(url,{
        'title':p_lang('更新运单')+'<span class="font-red">'+sn+'</span>'+p_lang('发货状态'),
        'width':'50%',
        'height':'80%',
        'lock':true
    });
}
function order_batch(cid,id,title)
{
    /*if(!id){
        $.dialog.alert(p_lang('该批次下没有运单，不能执行发货操作'));
        return false;
    }*/
	if(id==0){
        $.dialog.alert(p_lang('该批次下没有运单，不能执行发货操作'));
        return false;
    }
    var url = get_url('batch','delivery','cid='+cid);
    $.dialog.open(url,{
        'title':p_lang('更新')+'<span class="red">'+title+'</span>'+p_lang('批次运单状态'),
        'width':'50%',
        'height':'100%',
        'lock':true
    });
}
function order_print(){
    var url = get_url('order','scan');
    $.dialog.open(url,{
        'title':p_lang('扫描打印运单'),
        'width':'490px',
        'height':'150px',
        'lock':true,
        'resize':false,
        'fixed':true
    });
}
/*function package_storage1(sn)
{
    var url = get_url('package','storage1','sn='+sn);
    $.dsy.go(url);
}*/
function package_storage2(sn)
{
    var url = get_url('package','storage','sn='+sn);
    direct(url);
}

function package_storage(sn)
{
    var url = get_url('package','storage');
    if(sn){
        url += '&sn='+sn;
        var title = '包裹：'+sn+'入库操作';
    }else{
        var title = '扫描入库';
    }
    $.dialog.open(url,{
        'title':title,
        'lock':true,
        'width':'70%',
        'height':'100%',
        'ok':function(){
            var iframe = this.iframe.contentWindow;
            if (!iframe.document.body) {
                alert('iframe还没加载完毕呢');
                return false;
            };
            iframe.save();
            return false;
        },'okVal':'入库',
        'cancel':function(){
            return true;
        }
    });
}
//扫描出库
function order_out(sn,cid)
{
    var url = get_url('order','out','sn='+sn+'&cid='+cid);
    var title = '运单出库操作';
    $.dialog.open(url,{
        'title':title,
        'lock':true,
        'width':'70%',
        'height':'100%',
        'ok':function(){
            var iframe = this.iframe.contentWindow;
            if (!iframe.document.body) {
                alert('iframe还没加载完毕呢');
                return false;
            };
            iframe.save();
            return false;
        },'okVal':'出库',
        'cancel':function(){
            return true;
        }
    });
}
//单个产品出库
function order_out_single(id)
{
    var url = get_url('order','out_single','id='+id);
    var rs = $.dsy.json(url);
    if(rs.status == 'ok'){
        $.dialog.alert("出库成功",function(){
            $.dsy.reload();
        },'succeed');
    }else{
        $.dialog.alert(rs.content);
        return false;
    }
}
//待出库状态，发现管理员扣款错误操作
function order_error(sn)
{
    var url = get_url('order','error');
    url += '&sn='+sn;
    var title = '复重操作';
    $.dialog.open(url,{
        'title':title,
        'lock':true,
        'width':'70%',
        'height':'70%',
        'ok':function(){
            var iframe = this.iframe.contentWindow;
            if (!iframe.document.body) {
                alert('iframe还没加载完毕呢');
                return false;
            };
            iframe.save();
            return false;
        },'okVal':'重新扣款',
        'cancel':function(){
            return true;
        }
    });
}
function order_charged()
{
    var url = get_url('order','charged');
    var title = '扫描扣款';
    /*$.dialog.open(url,{
        'title':title,
        'width':'50%',
        'height':'100%',
        'lock':true
    });*/
    $.dialog.open(url,{
       'title':title,
        'lock':true,
        'width':'50%',
        'height':'100%',
        'ok':function(){
            var iframe = this.iframe.contentWindow;
            if (!iframe.document.body) {
                alert('iframe还没加载完毕呢');
                return false;
            };
            iframe.save();
            return false;
        },'okVal':'批量扣款',
        'cancel':function(){
            return true;
        }
   });
}
/*function order_out2(sn,cid)
{
    var url = get_url('order','out1','sn='+sn+'&cid='+cid);
    direct(url);
}*/

//后台包裹下拉操作
function list_action_exec()
{
    var ids = $.input.checkbox_join();
    if(!ids){
        $.dialog.alert(p_lang('未指定要操作的包裹'));
        return false;
    }
    var val = $("#list_action_val").val();
    if(!val || val == ''){
        $.dialog.alert(p_lang('未指定要操作的动作'),'','error');
        return false;
    }
    /*if(val.split('|')[0] == 'track'){
        set_track();
        return false;
    }
    if(val == 'appoint'){
        set_admin_id(ids);
        return false;
    }*/
    if(val == 'delete'){
        set_delete();
        return false;
    }
    /*if(val == 'sort'){
        set_sort();
        return false;
    }*/
    //执行批量审核通过
    if(val =='create' || val =='pickup' || val =='paid' || val =='shipped' || val =='unpaid' || val =='received' || val =='finished'){
        var url = get_url('order','execute','ids='+$.str.encode(ids)+"&status="+val);
    }
    else{
        var tmp = val.split(':');
        if(tmp[1] && tmp[0] == 'attr'){
            var type = $("#attr_set_val").val();
            url = get_url('package','attr_set','ids='+$.str.encode(ids)+'&val='+tmp[1]+'&type='+type);
        }else{
            var type = $("#cate_set_val").val();
            var url = get_url('order',"move_cate")+"&ids="+$.str.encode(ids)+"&cate_id="+tmp[1]+"&type="+type;
        }
    }
    //$.dialog.tips('正在执行操作，请稍候…');
    var rs = $.dsy.json(url);
    if(rs.status == 'ok'){
        $.dialog.alert(rs.content,function(){
            $.dsy.reload();
        },'succeed');
    }else{
        $.dialog.alert(rs.content);
        return false;
    }
}
//后台批量发货
function set_track()
{
    var ids = $.input.checkbox_join();
    if(!ids){
        $.dialog.alert("未指定要发货的运单");
        return false;
    }
    var val = $("#list_action_val").val();
    var addtime = $("[name=addtime]").val();
    var url = get_url("order","set_track")+"&id="+$.str.encode(ids)+"&title="+val.split('|')[1]+"&shipped="+val.split('|')[2]+"&addtime="+addtime;
    //direct(url);
    $.dialog.tips('正在执行操作，请稍候…');
    var rs = $.dsy.json(url);
    if(rs.status == 'ok'){
        $.dsy.reload();
    }else{
        $.dialog.alert(rs.content);
        return false;
    }
}
//后台批量删除
function set_delete()
{
    var ids = $.input.checkbox_join();
    if(!ids)
    {
        $.dialog.alert("未指定要删除的包裹");
        return false;
    }
    $.dialog.confirm("确定要删除选定的包裹吗？<br />删除后是不能恢复的？",function(){
        var url = get_url("package","deletes") +"&id="+$.str.encode(ids);
        var rs = json_ajax(url);
        if(rs.status == "ok")
        {
            $.dsy.reload();
        }
        else
        {
            $.dialog.alert(rs.content);
            return false;
        }
    });
}

//后台装袋功能
function update_select()
{
    var val = $("#list_action_val").val();
    if(val.substr(0,5) == 'attr:'){
        $("#attr_set_li").show();
    }else{
        $("#attr_set_li").hide();
    }
    if(val.substr(0,5) == 'cate:'){
        $("#cate_set_li").show();
    }else{
        $("#cate_set_li").hide();
    }
    if(val.substr(0,5) == 'track'){
        $("#track_set_li").show();
    }else{
        $("#track_set_li").hide();
    }
}
function order_dayin(id,mb){
    var url = get_url('order','dayin','id='+id+'&mb='+mb);
    direct(url);
}
//后台批量导出
function order_export(){
    var url = get_url('order','export');
    $.dialog.open(url,{
        "title":"数据导出",
        "width":"700px",
        "height":"60%",
        "lock":true
    });
}
function order_modify(){
    var url = get_url('order','modify');
    $.dialog.open(url,{
        "title":"批量更新快递",
        "width":"50%",
        "height":"70%",
        "lock":true
    });
}
function order_import(){
    var url = get_url('order','import');
    $.dialog.open(url,{
        "title":"批量创建运单",
        "width":"70%",
        "height":"90%",
        "lock":true
    });
}
function order_track(sn){
    var url = get_url('index','search','sn='+sn);
    $.dialog.open(url,{
        "title":'运单：'+sn+'物流跟踪信息',
        "width":"70%",
        "height":"70%",
        "lock":true
    });
}

function font_print(id,sn)
{
    var url = get_url('order','print','id='+id);
    var title = '运单：'+sn+'打印';
    $.dialog.open(url,{
        'title':title,
        'lock':true,
        'width':'70%',
        'height':'70%',
        'ok':function(){
            var iframe = this.iframe.contentWindow;
            if (!iframe.document.body) {
                alert('iframe还没加载完毕呢');
                return false;
            };
            iframe.window.print();
            $.dsy.reload();
            //return false;
        },'okVal':'打印',
        'cancel':function(){
            return true;
        }
    });
}
function back_print(id,sn)
{
    var url = get_url('order','print2','id='+id);
    var title = '包裹：'+sn+'打印';
    $.dialog.open(url,{
        'title':title,
        'lock':true,
        'width':'70%',
        'height':'70%',
        'ok':function(){
            var iframe = this.iframe.contentWindow;
            if (!iframe.document.body) {
                alert('iframe还没加载完毕呢');
                return false;
            };
            iframe.window.print();
            //$.dsy.reload();
            $.dialog.close();
            //return false;
        },'okVal':'打印',
        'cancel':function(){
            return true;
        }
    });
}
function removes()
{
    var ids = $.input.checkbox_join();
    if(!ids)
    {
        $.dialog.alert("未指定要删除的运单");
        return false;
    }
    $.dialog.confirm("确定要删除选定的运单吗？",function(){
        var url = get_url("order","removes") +"&id="+$.str.encode(ids);
        var rs = json_ajax(url);
        if(rs.status == "ok")
        {
            $.dsy.reload();
        }
        else
        {
            $.dialog.alert(rs.content);
            return false;
        }
    });
}
//批量清空回收站
function order_deletes()
{
    var ids = $.input.checkbox_join();
    if(!ids)
    {
        $.dialog.alert("未指定要删除的运单");
        return false;
    }
    $.dialog.confirm("确定要删除回收站选定的运单吗？<br />删除后是不能恢复的？",function(){
        var url = get_url("order","deletes") +"&id="+$.str.encode(ids);
        var rs = json_ajax(url);
        if(rs.status == "ok")
        {
            $.dsy.reload();
        }
        else
        {
            $.dialog.alert(rs.content);
            return false;
        }
    });
}
function order_payment(id,sn){
    var url = get_url('order','payment','id='+id);
    var title = '包裹：'+sn+'扣款';
    $.dialog.open(url,{
        'title':title,
        'lock':true,
        'width':'30%',
        'height':'30%',
        'ok':function(){
            var iframe = this.iframe.contentWindow;
            if (!iframe.document.body) {
                alert('iframe还没加载完毕呢');
                return false;
            };
            iframe.save();
            return false;
        },'okVal':'扣款',
        'cancel':function(){
            return true;
        }
    });
}
//批量扣款
function order_pays()
{
    var ids = $.input.checkbox_join();
    if(!ids)
    {
        $.dialog.alert("请选择要批量扣款的运单");
        return false;
    }
    var url = get_url('order','pays')+"&id="+$.str.encode(ids);
    var title = '批量扣款';
    $.dialog.open(url,{
        'title':title,
        'lock':true,
        'width':'50%',
        'height':'100%',
        'ok':function(){
            var iframe = this.iframe.contentWindow;
            if (!iframe.document.body) {
                alert('iframe还没加载完毕呢');
                return false;
            };
            iframe.save();
            return false;
        },'okVal':'批量扣款',
        'cancel':function(){
            return true;
        }
    });
}
function order_renew(id,title)
{
    $.dialog.confirm('确定要还原运单：<span class="font-red">'+title+'</span> 吗?',function(){
        var url = get_url('order','renew','id='+id);
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $.dsy.reload();
        }
        else
        {
            $.dialog.alert(rs.content);
            return false;
        }
    });
}
function order_weight()
{
    var url = get_url('order','weight');
    var title = '批量上传重量';
    $.dialog.open(url,{
        'title':title,
        'width':'50%',
        'height':'70%',
        'lock':true
    });
}
function order_end(id)
{
    var url = get_url('order','end','id='+id);
    var rs = $.dsy.json(url);
    if(rs.status == 'ok'){
        $.dialog.alert("已签收",function(){
            $.dsy.reload();
        },'succeed');
    }else{
        $.dialog.alert(rs.content);
        return false;
    }
}
//审核订单
function order_check(id,pageurl)
{
    var url;
    if(pageurl){
        pageurl = pageurl.replace(/\&/g, "%26");//"&"
        url = get_url('order','check','id='+id+'&pageurl='+pageurl);
    }else{
        url = get_url('order','check','id='+id);
    }
    $.dsy.go(url);
}
function chkorder_delete(id,title)
{
    $.dialog.confirm('确定要删除订单：<span class="font-red">'+title+'</span> 吗?<br />删除后您不能再恢复，请慎用<br /><span class="darkblue">删除成功后系统会自动刷新当前页面且不提示</span>',function(){
        var url = get_url('order','chkdelete','id='+id);
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $.dsy.reload();
        }
        else
        {
            $.dialog.alert(rs.content);
            return false;
        }
    });
}
//检查添加操作
function check_add()
{
	var url = get_url("user","chk");
	var id = $("#id").val();
	if(id && id != "undefined"){
		url += "&id="+id;
	}
	var user = $("#user").val();
	if(!user || user == "undefined"){
		$.dialog.alert("会员账号不能为空");
		return false;
	}
	url += "&user="+$.str.encode(user);
    /*var pass = $("#pass").val();
    if(!pass || pass == "undefined"){
        $.dialog.alert("会员密码不能为空");
        return false;
    }*/
	var mobile = $("#mobile").val();
	if(!mobile || mobile == "undefined"){
		$.dialog.alert("手机号不能为空");
		return false;
	}
	if(mobile){
		url += "&mobile="+$.str.encode(mobile);
	}
	var email = $("#email").val();
	if(!email || email == "undefined"){
		$.dialog.alert("邮箱不能为空");
		return false;
	}
	if(email){
		url += "&email="+$.str.encode(email);
	}
	var rs = $.dsy.json(url);
	if(rs.status != "ok"){
		$.dialog.alert(rs.content);
		return false;
	}
	return true;
}

function del(id)
{
	if(!id)
	{
		alert("操作非法");
		return false;
	}
	var q = confirm("确定要删除此信息吗？删除后是不能恢复的");
	if(q != 0)
	{
		var url = get_url("user","ajax_del") + "&id="+id;
		var msg = get_ajax(url);
		if(!msg) msg = "error: 操作非法";
		if(msg == "ok")
		{
			window.location.reload();
		}
		else
		{
			alert(msg);
			return false;
		}
	}
}

//更改权限状态
function set_status(id)
{
	if(!id)
	{
		alert("操作非法");
		return false;
	}
	var t = $("#status_"+id).attr("value");
	if(t == 2)
	{
		$.dialog.alert("此会员已被锁定，请点编辑后进行解除锁定");
		return false;
	}
	var url = get_url("user","ajax_status") + "&id="+id;
	var msg = get_ajax(url);
	if(msg == "ok")
	{
		var n_t = t == 1 ? 0 : 1;
		$("#status_"+id).removeClass("status"+t).addClass("status"+n_t);
		$("#status_"+id).attr("value",n_t);
		return true;
	}
	else
	{
		if(!msg) msg = "error: 操作非法";
		alert(msg);
		return false;
	}
}
function action_wealth_select(val)
{
	if(val == '1'){
		$("#a_html").html('增加');
		$("#a_type").val("+");
	}else{
		$("#a_html").html('减少');
		$("#a_type").val("-");
	}
}
function convert()
{
    var val = $("#a_val").val();
    var url = get_url("currency","get_currency");
    var rs = $.dsy.json(url);
    if(rs.status == "ok"){
        var hl = rs.content.val;
    }
    $("#usd").text("（$"+Math.round(parseFloat(val)/hl*100)/100+"）");//保留2位小数
}
function show_wealth_log(title,wid,uid)
{
	var url = get_url('wealth','log','wid='+wid+"&uid="+uid);
	$.dialog.open(url,{
		'title':title+p_lang('日志'),
		'lock':true,
		'width':'500px',
		'height':'400px',
		'ok':function(){
			return true;
		},
		'okVal':'关闭'
	});
}

function show_address(title,uid)
{
	var url = get_url('user','address','id='+uid);
	$.dialog.open(url,{
		'title':title+p_lang('地址库')+p_lang('，每个会员最多30个地址'),
		'width':'700px',
		'height':'400px',
		'lock':true,
		'cancel':function(){
			return true;
		},
		'cancelVal':'关闭窗口'
	});
}

function show_invoice(title,uid)
{
	var url = get_url('user','invoice','id='+uid);
	$.dialog.open(url,{
		'title':title+p_lang('发票设置')+p_lang('，每个会员最多10条发票设置'),
		'width':'500px',
		'height':'400px',
		'lock':true,
		'cancel':function(){
			return true;
		},
		'cancelVal':'关闭窗口'
	});
}
function update_select()
{
    var val = $("#list_action_val").val();
    if(val.substr(0,5) == 'cate:'){
        $("#cate_set_li").show();
    }else{
        $("#cate_set_li").hide();
    }
}
function list_action_exec()
{
    var ids = $.input.checkbox_join();
    if(!ids){
        $.dialog.alert(p_lang('未指定要操作的会员'));
        return false;
    }
    var val = $("#list_action_val").val();
    if(!val || val == ''){
        $.dialog.alert(p_lang('未指定要操作的动作'),'','error');
        return false;
    }

    var tmp = val.split(':');
    var type = $("#cate_set_val").val();
    var url = get_url('user',"move_cate")+"&ids="+$.str.encode(ids)+"&group_id="+tmp[1]+"&type="+type;

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
function action_wealth(title,wid,uid,backurl)
{
    var url = get_url('wealth','action_user','wid='+wid+"&uid="+uid+"&backurl"+backurl);
    $.dialog.open(url,{
        'title':p_lang('会员'+title+'操作'),
        'lock':true,
        'width':'50%',
        'height':'50%',
        'ok':function(){
            var iframe = this.iframe.contentWindow;
            if (!iframe.document.body) {
                alert('iframe还没加载完毕呢');
                return false;
            };
            iframe.save();
            return false;
        },
        'okVal':'提交保存',
        'cancel':true
    })
}
function set_sort()
{
	var ids = $.input.checkbox_join();
	if(!ids)
	{
		$.dialog.alert("未指定要排序的ID");
		return false;
	}
	var url = get_url("stock","sort");
	var list = ids.split(",");
	for(var i in list)
	{
		var val = $("#taxis_"+list[i]).val();
		url += "&sort["+list[i]+"]="+val;
	}
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
}

function check_save()
{
	var title = $("#title").val();
	if(!title)
	{
        $.dialog.alert('仓库名称不能为空',function(){
            $("#title").focus();
        },'succeed');
        return false;
	}
	var province = $("#province").val();
	if(!province)
	{
        $.dialog.alert('州/省不能为空',function(){
            $("#province").focus();
        },'succeed');
        return false;
	}
	var city = $("#city").val();
	if(!city)
	{
        $.dialog.alert('城市不能为空',function(){
            $("#city").focus();
        },'succeed');
        return false;
	}
	var address =$("#address").val();
	if(!address)
	{
        $.dialog.alert('地址不能为空',function(){
            $("#address").focus();
        },'succeed');
        return false;
	}
	var zipcode = $("#zipcode").val();
	if(!zipcode)
	{
        $.dialog.alert('邮编不能为空',function(){
            $("#zipcode").focus();
        },'succeed');
        return false;
	}
    var consignor = $("#consignor").val();
    if(!consignor)
    {
        $.dialog.alert('发货人不能为空',function(){
            $("#consignor").focus();
        },'succeed');
        return false;
    }
	var tel = $("#tel").val();
	if(!tel)
	{
        $.dialog.alert('电话不能为空',function(){
            $("#tel").focus();
        },'succeed');
        return false;
	}
	return true;
}

function stock_del(id,title)
{
	$.dialog.confirm("确定要删除：<span class='font-red'>"+title+"</span>?",function(){
		var url = get_url('stock','delete','id='+id);
		var rs = json_ajax(url);
		if(rs.status == 'ok')
		{
            $.dsy.reload();
		}
		else
		{
			if(!rs.content) rs.content = '删除失败';
			$.dialog.alert(rs.content);
			return false;
		}
	});
}
function batch_del(id,title)
{
    $.dialog.confirm("确定要删除：<span class='red'>"+title+"</span>！",function(){
        var url = get_url('batch','delete','id='+id);
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $.dialog.alert("批次：<span class='red'>"+title+"</span> 删除成功",function(){
                $.dsy.reload();
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
function code_del(id,title)
{
    $.dialog.confirm("确定要删除：<span class='red'>"+title+"</span>！",function(){
        var url = get_url('code','delete','id='+id);
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $.dialog.alert("报关条码码：<span class='red'>"+title+"</span> 删除成功",function(){
                $.dsy.reload();
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
function set_delete()
{
    var ids = $.input.checkbox_join();
    if(!ids)
    {
        $.dialog.alert("未指定要删除的报关条码");
        return false;
    }
    $.dialog.confirm("确定要删除选定的报关条码吗？<br />删除后是不能恢复的？",function(){
        var url = get_url("code","deletes") +"&id="+$.str.encode(ids);
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
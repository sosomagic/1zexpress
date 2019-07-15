function set_sort()
{
	var ids = $.input.checkbox_join();
	if(!ids)
	{
		$.dialog.alert("未指定要排序的ID");
		return false;
	}
	var url = get_url("custom","sort");
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
    var cate_id = $("#cate_id").val();
   /* if(!cate_id)
    {
        $.dialog.alert("请选择服务类别");
        return false;
    }*/
	var title = $("#title").val();
	if(!title)
	{
		$.dialog.alert("服务名称不能为空");
		return false;
	}
	
	var price = $("#price").val();
	var preg= /^\d+(\.\d+)?$/; 
    if(!preg.test(price)){
		$.dialog.alert("费用请输入正确数字格式");
		return false;
	}	
	return true;
}

function custom_del(id,title)
{
	$.dialog.confirm("确定要删除：<span class='red'>"+title+"</span>，请慎用！",function(){
		var url = get_url('custom','delete','id='+id);
		var rs = json_ajax(url);
		if(rs.status == 'ok')
		{
			$.dialog.alert("服务：<span class='red'>"+title+"</span> 删除成功",function(){
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
        $.dialog.alert("未指定要删除的服务");
        return false;
    }
    $.dialog.confirm("确定要删除选定的服务吗？<br />删除后是不能恢复的？",function(){
        var url = get_url("custom","deletes") +"&id="+$.str.encode(ids);
        var rs = json_ajax(url);
        if(rs.status == "ok")
        {
            //$.dialog.alert("包裹删除成功",function(){
            //window.location.reload();
            //});
            $.dsy.reload();
        }
        else
        {
            $.dialog.alert(rs.content);
            return false;
        }
    });
}
/***********************************************************
 Filename: js/channel.js
 Note	: 发货渠道管理中涉及到的JS
 Version : 2.0
 Update  : 16:53 2016-1-9
 ***********************************************************/
function set_sort()
{
    var ids = $.input.checkbox_join();
    if(!ids)
    {
        $.dialog.alert("未指定要排序的ID");
        return false;
    }
    var url = get_url("channel","sort");
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
    var preg= /^\d+(\.\d+)?$/;
    var title = $("#title").val();
    if(!title)
    {
        $.dialog.alert("渠道名称不能为空");
        return false;
    }
    return true;
}
function channel_del(id,title)
{
    $.dialog.confirm("确定要删除：<span class='red'>"+title+"</span>，请慎用！",function(){
        var url = get_url('channel','delete','id='+id);
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $.dialog.alert("线路：<span class='red'>"+title+"</span> 删除成功",function(){
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
function track_del(id,title)
{
    $.dialog.confirm("确定要删除：<span class='red'>"+title+"</span>，请慎用！",function(){
        var url = get_url('channel','track_delete','id='+id);
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $.dialog.alert("发货状态：<span class='red'>"+title+"</span> 删除成功",function(){
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
function tax_del(id,title)
{
    $.dialog.confirm("确定要删除：<span class='red'>"+title+"</span>，请慎用！",function(){
        var url = get_url('tax','delete','id='+id);
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $.dialog.alert("完税价格：<span class='red'>"+title+"</span> 删除成功",function(){
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
function tax_save()
{
    var preg= /^\d+(\.\d+)?$/;
    var cate_id = $("#cate_id").val();
    if(!cate_id)
    {
        $.dialog.alert("请选择商品分类");
        return false;
    }
    var title = $("#title").val();
    if(!title)
    {
        $.dialog.alert("商品名称不能为空");
        return false;
    }
    var code = $("#code").val();
    if(!code)
    {
        $.dialog.alert("商品货号不能为空");
        return false;
    }
    var tax_number = $("#tax_number").val();
    if(!tax_number)
    {
        $.dialog.alert("行邮税号不能为空");
        return false;
    }
    var spec = $("#spec").val();
    if(!spec)
    {
        $.dialog.alert("规格型号不能为空");
        return false;
    }

    var weight = $("#weight").val();
    if(!preg.test(weight)){
        $.dialog.alert("重量不能为空");
        return false;
    }
    var unit = $("#unit").val();
    if(!unit)
    {
        $.dialog.alert("单位代码不能为空");
        return false;
    }
    var price = $("#price").val();
    if(!preg.test(price)){
        $.dialog.alert("完税价格不能为空");
        return false;
    }
    var taxes = $("#taxes").val();
    if(!preg.test(taxes)){
        $.dialog.alert("税率不能为空");
        return false;
    }
    return true;
}
function check_price()
{
    var group_id = $("#group_id").val();
    if(!group_id)
    {
        $.dialog.alert("请选择所属会员组");
        return false;
    }
    var channel_id = $("#channel_id").val();
    if(!channel_id)
    {
        $.dialog.alert("请选择所属线路");
        return false;
    }
    if($('#remove').is(':checked')) {
        var num = $('#num').val();
        if(!preg.test(num)||num=='0'||num=='0.00' ||num>='1'){
            $.dialog.alert("请填写要抹零的小数");
            return false;
        }
    }
    var rule = $("input[name='rule']:checked").val();
    if(!rule){
        $.dialog.alert("请选择计重模式");
        return false;
    }
    var start_heavy = $("#start_heavy").val();
    if(!preg.test(start_heavy) || start_heavy=='0.00'){
        $.dialog.alert("请填写起步重量");
        return false;
    }
    var start_price = $("#start_price").val();
    if(!preg.test(start_price) || start_price=='0.00'){
        $.dialog.alert("请填写起步运费");
        return false;
    }
    var first = $("#first").val();
    if(!preg.test(first) || first=='0.00'){
        $.dialog.alert("请填写第1阶段计费单位");
        return false;
    }
    var first_price = $("#first_price").val();
    if(!preg.test(first_price) || first_price=='0.00'){
        $.dialog.alert("请填写第1阶段运费单价");
        return false;
    }
    return true;
}
function price_del(id,title)
{
    $.dialog.confirm("确定要删除：<span class='red'>"+title+"</span>，请慎用！",function(){
        var url = get_url('channel','price_delete','id='+id);
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $.dialog.alert("线路资费：<span class='red'>"+title+"</span> 删除成功",function(){
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
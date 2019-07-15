<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('barcode');?>" title="返回条码列表">条码管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>条码列表</span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="navbar-collapse bg-white" style="margin-bottom: 10px;">
            <form class="navbar-form navbar-left" method="post" action="<?php echo dsy_url(array('ctrl'=>'barcode'));?>">
                <div class="row">
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="">状态</option>
                            <option value="0"<?php if($status == 0 && $status!=''){ ?> selected<?php } ?>>未使用</option>
                            <option value="1"<?php if($status == 1){ ?> selected<?php } ?>>已使用</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="title" value="<?php echo $title;?>" placeholder="条码单号" />
                    </div>
                    <button class="btn btn-info" type="submit"><i class="fa fa-search"></i> 查 询 </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-th-list font-red"></i>
                    <span class="caption-subject font-red sbold uppercase">条码列表</span>
                </div>
                <div class="pull-right" >
                    <a class="btn green" href="javascript:;" onclick="addBarcode()">
                        <i class="fa fa-plus"></i>
                        添加条码
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th class="bold text-center">条码单号</th>
                        <th class="bold text-center">时间</th>
                        <th class="bold text-center">状态</th>
                        <th class="bold text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td class="text-center">
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input class="checkboxes" id="id_<?php echo $value['id'];?>" name="ids[]" value="<?php echo $value['id'];?>" type="checkbox">
                                <span></span>
                            </label>
                        </td>
                        <td class="text-center"><a href="javascript:;" onclick="showBarcode('<?php echo $value['id'];?>')"><?php echo $value['title'];?></a></td>
                        <td class="status text-center"><?php echo date('Y-m-d H:i:s',$value['addtime']);?></td>
                        <td class="status text-center"><?php echo $value['status'] ? '已使用' : '未使用';?></td>
                        <td class="text-center">
                            <!--<button class="btn btn-sm btn-info" onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'barcode','func'=>'edit','id'=>$value['id']));?>')">
                                <i class="fa fa-edit"></i> 编辑
                            </button>-->
                            <button class="btn btn-sm btn-danger" onclick="del('<?php echo $value['id'];?>','<?php echo $value['title'];?>')">
                                <i class="fa fa-times"></i> 删除
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-6 form-inline">
                        <div class="form-group">
                            <input type="button" value="全选" class="btn btn-sm btn-danger" onclick="$.input.checkbox_all()" />
                            <input type="button" value="全不选" class="btn btn-sm btn-success" onclick="$.input.checkbox_none()" />
                            <input type="button" value="反选" class="btn btn-sm btn-warning" onclick="$.input.checkbox_anti()" />
                        </div>
                        <div class="form-group" id="plugin_button"><input type="button" value="批量删除" onclick="set_delete()" class="btn btn-sm btn-danger" /></div>
                        <a class="btn btn-sm btn-info" href="javascript:void(0);" onclick="prints()">
                            <i class="fa fa-close"></i>
                            批量打印
                        </a>
                    </div>
                    <?php if($pagelist){ ?> <div class="col-md-6 text-right" style="font-size: 12px;"><?php $this->output("pagelist","file"); ?></div><?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".status").each(function(){
            if($(this).text() == '已使用'){
                $(this).addClass('font-red');
            }
        });
    });
</script>
<script type="text/javascript">
    function del(id,title)
    {
        $.dialog.confirm("确定要删除：<span class='font-red'>"+title+"</span>！",function(){
            var url = get_url('barcode','delete','id='+id);
            var rs = json_ajax(url);
            if(rs.status == 'ok')
            {
                $.dialog.alert("条码：<span class='red'>"+title+"</span> 删除成功",function(){
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
            var url = get_url("barcode","deletes") +"&id="+$.str.encode(ids);
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
    function addBarcode()
    {
        var url = get_url('barcode','add');
        var title = '添加条码单号';
        $.dialog.open(url,{
            'title':title,
            'width':'100%',
            'height':'100%',
            'lock':true
        });
    }
    function showBarcode(id)
    {
        var url = get_url('barcode','show','id='+id);
        var title = '条码展示';
        $.dialog.open(url,{
            'title':title,
            'width':'50%',
            'height':'50%',
            'lock':true
        });
    }
    function prints()
    {
        var ids = $.input.checkbox_join();
        if(!ids)
        {
            $.dialog.alert("请选择要批量打印的条码单号");
            return false;
        }
        var url = get_url('barcode','prints')+"&id="+$.str.encode(ids);
        var title = '批量打印';
        $.dialog.open(url,{
            'title':title,
            'lock':true,
            'width':'100%',
            'height':'100%',
            'ok':function(){
                var iframe = this.iframe.contentWindow;
                if (!iframe.document.body) {
                    alert('iframe还没加载完毕呢');
                    return false;
                }
                iframe.window.print();
                $.dialog.close();
            },'okVal':'打印',
            'cancel':function(){
                return true;
            }
        });
    }
</script>
<?php $this->output("foot","file"); ?>
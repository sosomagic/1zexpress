<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('code');?>" title="返回报国内快递单号">国内快递单号</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>国内快递单号</span>
        </li>
    </ul>
</div>
<div class="page-bar">
    <form class="navbar-form navbar-left" method="post" action="<?php echo dsy_url(array('ctrl'=>'number'));?>">
        <div class="row">
            <div class="form-group">
                <select name="status" class="form-control">
                    <option value="">状态</option>
                    <option value="0"<?php if($status == 0 && $status!=''){ ?> selected<?php } ?>>未使用</option>
                    <option value="1"<?php if($status == 1){ ?> selected<?php } ?>>已使用</option>
                </select>
            </div>
            <div class="form-group">
                <select id="channel" name="cid" class="form-control">
                    <option value="">发货渠道</option>
                    <?php $channel_id["num"] = 0;$channel=is_array($channel) ? $channel : array();$channel_id["total"] = count($channel);$channel_id["index"] = -1;foreach($channel AS $key=>$value){ $channel_id["num"]++;$channel_id["index"]++; ?>
                    <option value="<?php echo $value['id'];?>"<?php if($value['id'] == $cid){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <input class="form-control input-small" type="text" name="title" value="<?php echo $title;?>" placeholder="国内快递单号" />
            </div>
            <div class="form-group">
                <button class="btn btn-info" type="submit"><i class="fa fa-search"></i> 查 询 </button>
            </div>
            <?php if($popedom['add']){ ?>
            <div class="form-group" >
                <a class="btn green" href="<?php echo admin_url('number','set');?>">
                    <i class="fa fa-plus"></i>
                    导入快递单号
                </a>
            </div>
            <?php } ?>
            </div>
    </form>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="tabbable-line">
                <ul class="nav nav-tabs">
                    <li class="<?php if(!$cid){ ?>active <?php } ?>bold"><a href="javascript:void(0)" onclick="tab('')">全部单号<span class="badge badge-success"><?php echo $sum;?></span></a></li>
                    <?php $channel_id["num"] = 0;$channel=is_array($channel) ? $channel : array();$channel_id["total"] = count($channel);$channel_id["index"] = -1;foreach($channel AS $key=>$value){ $channel_id["num"]++;$channel_id["index"]++; ?>
                    <li class="<?php if($cid==$value['id']){ ?>active <?php } ?>bold"><a href="javascript:void(0)" onclick="tab('<?php echo $value['id'];?>')"><?php echo $value['title'];?><span class="badge badge-success"> <?php echo $count[$key];?> </span></a></li>
                    <?php } ?>
                </ul>
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th class="bold text-center">单号</th>
                        <th class="bold text-center">快递公司</th>
                        <th class="bold text-center">发货渠道</th>
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
                        <td class="text-center"><?php echo $value['title'];?></td>
                        <td class="text-center"><?php echo $value['express']['title'];?></td>
                        <td class="text-center"><?php echo $value['channel']['title'];?></td>
                        <td class="status text-center"><?php echo $value['status'] ? '已使用' : '未使用';?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info" onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'number','func'=>'edit','id'=>$value['id']));?>')">
                                <i class="fa fa-edit"></i> 编辑
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="code_del('<?php echo $value['id'];?>','<?php echo $value['title'];?>')">
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
    function tab(cid){
        var url;
        url = get_url('number','index','cid='+cid);
        $.dsy.go(url);
    }
    function code_del(id,title)
    {
        $.dialog.confirm("确定要删除：<span class='font-red'>"+title+"</span>！",function(){
            var url = get_url('number','delete','id='+id);
            var rs = json_ajax(url);
            if(rs.status == 'ok')
            {
                $.dialog.alert("快递单号：<span class='red'>"+title+"</span> 删除成功",function(){
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
            var url = get_url("number","deletes") +"&id="+$.str.encode(ids);
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
</script>
<?php $this->output("foot","file"); ?>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'admin'));?>">管理员维护</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>管理员列表</span>
        </li>
    </ul>
    <div class="pull-right" style="padding: 5px 10px 5px 0">
        <a class="btn green" href="<?php echo dsy_url(array('ctrl'=>'admin','func'=>'set'));?>">
            <i class="fa fa-plus"></i>
            添加管理员
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                        <tr>
                            <th class="col-md-2 bold">账号</th>
                            <th class="col-md-1 bold">状态</th>
                            <th class="col-md-2 bold">邮箱</th>
                            <th class="col-md-5 bold">仓库管理</th>
                            <th class="col-md-2 bold">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                        <tr>
                            <td><?php echo $value['account'];?><?php if($value['if_system']){ ?><span class="red i"><?php echo P_Lang("（系统管理员）");?></span><?php } ?></td>
                            <td><?php if($value['status']){ ?>开通<?php } else { ?>关闭<?php } ?></td>
                            <td><?php echo $value['email'];?></td>
                            <td><?php $list_id["num"] = 0;$value['stock']=is_array($value['stock']) ? $value['stock'] : array();$list_id["total"] = count($value['stock']);$list_id["index"] = -1;foreach($value['stock'] AS $k=>$v){ $list_id["num"]++;$list_id["index"]++; ?><?php echo $v['title'];?><?php if($list_id['num'] != $list_id['total']){ ?> 、 <?php } ?><?php } ?></td>
                            <td>
                                <?php if($popedom['modify']){ ?>
                                <a class="btn btn-xs btn-info" href="javascript:admin_edit(<?php echo $value['id'];?>);">
                                    <i class="fa fa-edit"></i>
                                    编辑
                                </a>
                                <?php } ?>
                                <?php if($popedom['delete']){ ?>
                                <a class="btn btn-xs btn-danger" <?php if(!$value['if_system'] && $value['id'] != $session['admin_id']){ ?>onclick="admin_delete('<?php echo $value['id'];?>','<?php echo $value['account'];?>')"<?php } else { ?>onclick="$.dialog.alert('<?php echo P_Lang("系统管理员或您自己不能删除");?>')"<?php } ?>>
                                <i class="fa fa-times"></i>
                                删除
                                </a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function admin_delete(id,title)
    {
        $.dialog.confirm("<?php echo P_Lang("确定要删除管理员");?> <span class='red'>"+title+"</span> <?php echo P_Lang("吗?");?>",function(){
            var url = get_url("admin","delete") +"&id="+id;
            var rs = json_ajax(url);
            if(rs.status != "ok")
            {
                $.dialog.alert(rs.content);
                return false;
            }
            else
            {
                //$("#admin_tr_"+id).remove();
                $.dsy.reload();
            }
        });
    }
    /*function set_status(id)
    {
        var url = get_url("admin","status") + '&id='+id;
        var rs = json_ajax(url);
        if(rs.status == "ok")
        {
            if(!rs.content) rs.content = '0';
            var oldvalue = $("#status_"+id).attr("value");
            var old_cls = "status"+oldvalue;
            $("#status_"+id).removeClass(old_cls).addClass("status"+rs.content).attr("value",rs.content);
        }
        else
        {
            $.dialog.alert(rs.content);
            return false;
        }
    }*/
    function admin_edit(id)
    {
        var url = get_url("admin","set") + '&id='+id;
        $.dsy.go(url);
    }
    /*$(document).ready(function(){
        top.$.desktop.title('<?php echo P_Lang("管理员维护");?>');
    });*/
</script>
<?php $this->output("foot","file"); ?>
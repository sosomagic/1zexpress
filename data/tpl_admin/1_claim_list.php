<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'claim'));?>">理赔管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>理赔列表</span>
        </li>
    </ul>
</div>
<div class="page-bar">
    <form class="navbar-form " method="post" action="<?php echo admin_url('claim');?>">
        <div class="row">
            <div class="form-group">
                <select class="form-control" name="status">
                    <option value="">请选择理赔状态</option>
                    <option value="1"<?php if($status==1){ ?> selected<?php } ?>>已处理</option>
                    <option value="0"<?php if($status===0){ ?> selected<?php } ?>>未处理</option>
                </select>
            </div>
            <div class="form-group">
                <input class="form-control" name="sn" value="<?php echo $sn;?>" placeholder="运单号" type="text">
            </div>
            <div class="form-group">
                <button class="btn btn-info" type="submit"><i class="fa fa-search"></i> 查 询 </button>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="col-md-1 bold">理赔运单号</th>
                        <th class="col-md-1 bold">会员</th>
                        <th class="col-md-1 bold">理赔类型</th>
                        <th class="col-md-2 bold">理赔说明</th>
                        <th class="col-md-2 bold">理赔要求</th>
                        <th class="col-md-1 bold">理赔凭证</th>
                        <th class="col-md-1 bold">理赔金额(美元)</th>
                        <th class="col-md-1 bold">申请时间</th>
                        <th class="col-md-1 bold">状态</th>
                        <th class="col-md-1 bold text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td><?php echo $value['sn'];?></td>
                        <td><?php echo $value['user']['user'];?></td>
                        <td><?php echo $value['type'];?></td>
                        <td><?php echo $value['detail'];?></td>
                        <td><?php echo $value['note'];?></td>
                        <td class="text-center"><?php if($value['pic']<>''){ ?><a href="javascript:show_pic('<?php echo $value['pic'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13"></a><?php } ?></td>
                        <td class="text-center"><?php echo $value['money'];?></td>
                        <td><?php echo date('Y-m-d H:i:s',$value['addtime']);?></td>
                        <td><?php if($value['status']){ ?>已处理<?php } else { ?><span class="font-red">未处理</span><?php } ?></td>
                        <td class="text-center">
                            <?php if($value['status']==0){ ?>
                            <?php if($popedom['modify']){ ?>
                            <a href="<?php echo dsy_url(array('ctrl'=>'claim','func'=>'set','id'=>$value['id']));?>" class="btn btn-xs btn-info">赔付</a>
                            <?php } ?>
                            <?php if($popedom['delete']){ ?><input type="button" value="删除" onclick="claim_delete('<?php echo $value['id'];?>','<?php echo $value['name'];?>')" class="btn btn-xs btn-danger" /><?php } ?>
                            <?php } else { ?>管理员回复：<?php echo $value['replay'];?><?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php if($pagelist){ ?><div class="text-right"><?php $this->output("pagelist","file"); ?></div><?php } ?>
            </div>
        </div>
    </div>
</div>
<script>
    function claim_delete(id,title)
    {
        $.dialog.confirm('确定要删除订单：<span class="red">'+title+'</span> 吗?<br />删除后您不能再恢复，请慎用<br /><span class="darkblue">删除成功后系统会自动刷新当前页面且不提示</span>',function(){
            var url = get_url('claim','delete','id='+id);
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
    function show_pic(id)
    {
        var url = get_url('claim','show_pic','id='+id);
        var title = '理赔凭证';
        $.dialog.open(url,{
            'title':title,
            'lock':true,
            'width':'50%',
            'height':'50%',
            'cancel':function(){
                return true;
            }
        });
    }
</script>
<?php $this->output("foot","file"); ?>
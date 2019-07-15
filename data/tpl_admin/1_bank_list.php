<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'bank_list'));?>">转账管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>转账列表</span>
        </li>
    </ul>
</div>
<div class="page-bar">
    <form class="navbar-form navbar-left" method="post" action="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'bank_list'));?>">
        <div class="form-group">
            <input class="form-control" type="text" name="user" value="<?php echo $user;?>" placeholder="会员名">
        </div>
        <div class="form-group">
            <input id="dateRange" class="form-control" type="text" name="dateRange" value="<?php echo $dateRange;?>" placeholder="日期范围"/>
        </div>
        <div class="form-group">
            <button class="btn btn-info" type="submit"><i class="fa fa-search"></i> 查 询 </button>
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
                        <th class="bold text-center">会员名</th>
                        <th class="bold text-center">转账截图</th>
                        <th class="bold text-center">金额</th>
                        <th class="bold text-center">日期</th>
                        <th class="bold text-center">状态</th>
                        <th class="bold text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td class="text-center"><?php echo $value['user']['user'];?></td>
                        <td class="text-center"><?php if($value['vpic']<>''){ ?><a href="javascript:show_pic('<?php echo $value['vpic'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13"></a><?php } ?></td>
                        <td class="text-center"><?php echo $value['money'];?></td>
                        <td class="text-center"><?php echo date('Y-m-d H:i:s',$value['addtime']);?></td>
                        <td class="text-center"><?php if($value['status']){ ?>已处理<?php } else { ?><span class="font-red">未处理</span><?php } ?></td>
                        <td class="text-center">
                            <?php if($value['status']==0){ ?>
                            <a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'bank_set','id'=>$value['id']));?>" class="btn btn-xs btn-info">编辑</a>
                            <?php if($popedom['delete']){ ?><input type="button" value="删除" onclick="bank_delete('<?php echo $value['id'];?>')" class="btn btn-xs btn-danger" /><?php } ?>
                            <?php } else { ?>已入账<?php } ?>
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
<script type="text/javascript" src="js/laydate/laydate.js"></script>
<script>
	//日期范围
    laydate.render({
        elem: '#dateRange'
        ,range: true
    });
    function bank_delete(id)
    {
        $.dialog.confirm('确定要删除转账凭证吗?<br />删除后您不能再恢复，请慎用<br /><span class="darkblue">删除成功后系统会自动刷新当前页面且不提示</span>',function(){
            var url = get_url('payment','bank_delete','id='+id);
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
        var title = '转账凭证';
        $.dialog.open(url,{
            'title':title,
            'lock':true,
            'width':'350px',
            'height':'350px',
            'cancel':function(){
                return true;
            }
        });
    }
</script>
<?php $this->output("foot","file"); ?>
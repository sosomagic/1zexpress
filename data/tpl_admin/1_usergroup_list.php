<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-red"></i>
                    <span class="caption-subject font-red sbold uppercase">会员等级列表</span>
                </div>
                <div class="pull-right" >
                    <a class="btn green" href="<?php echo admin_url('usergroup','set');?>">
                        <i class="fa fa-plus"></i>
                        添加会员等级
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="bold col-md-8">会员等级名</th>
                        <th class="bold col-md-2">排序</th>
                        <th class="bold text-center col-md-2">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td>
                            <?php echo $value['title'];?><?php if($value['is_open']){ ?><span class="font-blue"> (开放选择)</span><?php } ?>
                        </td>
                        <td><?php echo $value['taxis'];?></td>
                        <td class="text-center">
                            <?php if($popedom['modify']){ ?>
                            <a class="btn btn-xs blue" href="<?php echo dsy_url(array('ctrl'=>'usergroup','func'=>'set','id'=>$value['id']));?>">编辑</a>
                            <?php } ?>
                            <?php if($popedom['delete']){ ?>
                            <input type="button" value="删除" class="btn btn-xs btn-danger" onclick="to_del('<?php echo $value['id'];?>')" />
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function to_del(id)
    {
        if(!id)
        {
            $.dialog.alert("操作非法");
            return false;
        }
        var q = confirm("确定要删除此会员等级吗？删除后是不能恢复的");
        if(q != 0)
        {
            var url = get_url("usergroup","ajax_del") + "&id="+id;
            var msg = get_ajax(url);
            if(!msg) msg = "error: 操作非法";
            if(msg == "ok")
            {
                $.dsy.reload();
            }
            else
            {
                $.dialog.alert(msg);
                return false;
            }
        }
    }
</script>
<?php $this->output("foot","file"); ?>
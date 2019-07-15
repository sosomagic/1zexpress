<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('book');?>" title="返回工单列表">工单管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>工单列表</span>
        </li>
    </ul>
</div>
<div class="page-bar" style="padding-left: 10px;">
    <form id="search" class="navbar-form navbar-left" method="post" action="<?php echo admin_url('book');?>">
        <div class="row">
            <div class="form-group">
                <select id="status" name="status" class="form-control">
                    <option value="">全部工单</option>
                    <option value="0"<?php if($status <> null){ ?> selected<?php } ?>>未处理</option>
                    <option value="1"<?php if($status ==1){ ?> selected<?php } ?>>已处理</option>
                </select>
            </div>
            <div class="form-group">
                <select id="cate_id" name="cate_id" class="form-control">
                    <option value="">分类</option>
                    <?php $catelist_id["num"] = 0;$catelist=is_array($catelist) ? $catelist : array();$catelist_id["total"] = count($catelist);$catelist_id["index"] = -1;foreach($catelist AS $key=>$value){ $catelist_id["num"]++;$catelist_id["index"]++; ?>
                    <option value="<?php echo $value['id'];?>"<?php if($value['id'] == $cate_id){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="sn" value="<?php echo $sn;?>" placeholder="运单号" />
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="content" value="<?php echo $content;?>" placeholder="问题描述" />
            </div>
            <div class="form-group">
                <input id="dateRange" class="form-control" type="text" name="dateRange" value="<?php echo $dateRange;?>" placeholder="日期范围"/>
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
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                        <tr>
                            <th class="col-md-2 bold">运单号</th>
                            <th class="col-md-2 bold">描述</th>
                            <th class="col-md-1 bold">分类</th>
                            <th class="col-md-2 bold">备注</th>
                            <th class="col-md-2 bold">提交时间</th>
                            <th class="col-md-1 bold">会员</th>
                            <th class="col-md-1 bold">管理员回复</th>
                            <th class="col-md-1 bold">状态</th>
                            <th class="col-md-1 bold text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                        <tr>
                            <td><span class="font-blue">[<?php echo $value['level'];?>]</span><?php echo $value['sn'];?></td>
                            <td><?php echo $value['content'];?></td>
                            <td><?php echo $value['catename']['title'];?></td>
                            <td><?php echo $value['note'];?></td>
                            <td><?php echo date('Y-m-d H:i:s',$value['addtime']);?></td>
                            <td><?php echo $value['user']['user'];?></td>
                            <td><?php echo $value['adm_reply'];?></td>
                            <td><?php if($value['status']){ ?>已处理<?php } else { ?><span class="font-red">未处理</span><?php } ?></td>
                            <td class="text-center">
                                <?php if($popedom['modify']){ ?><input type="button" value="编辑" onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'book','func'=>'set','id'=>$value['id']));?>')" class="btn btn-xs btn-info" /><?php } ?>
                                <?php if($popedom['delete']){ ?><input type="button" value="删除" onclick="del('<?php echo $value['id'];?>','<?php echo $value['name'];?>')" class="btn btn-xs btn-danger" /><?php } ?>
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
</div>
<script type="text/javascript" src="js/laydate/laydate.js"></script>
<script>
	//日期范围
    laydate.render({
        elem: '#dateRange'
        ,range: true
    });
    function del(id,title)
    {
        $.dialog.confirm('确定要删除问题：<span class="red">'+title+'</span> 吗?<br />删除后您不能再恢复，请慎用<br /><span class="darkblue">删除成功后系统会自动刷新当前页面且不提示</span>',function(){
            var url = get_url('book','delete','id='+id);
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
   /* function show_pic(id)
    {
        var url = get_url('book','show_pic','id='+id);
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
    }*/
</script>
<?php $this->output("foot","file"); ?>
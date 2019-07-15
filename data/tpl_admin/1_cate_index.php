<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('cate');?>">分类管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>分类列表</span>
        </li>
    </ul>
    <div class="pull-right" style="padding: 5px 10px 5px 0">
        <a class="btn green" href="<?php echo admin_url('cate','set');?>">
            添加根分类
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
                            <th class="col-md-1 bold">ID</th>
                            <th class="col-md-5 bold">分类名</th>
                            <th class="col-md-1 bold">排序</th>
                            <th class="col-md-2 bold text-center">状态</th>
                            <th class="col-md-3 bold">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $tab_i = 0;;?>
                        <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                        <?php $tab_i++;?>
                        <tr>
                            <td><?php echo $value['id'];?></td>
                            <td>
                                <?php for($i=1;$i<$value['_layer'];$i++){ ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <?php } ?>
                                <?php if($value['_layer']>0){ ?>&nbsp;&nbsp;&nbsp;&nbsp;<span class="fa fa-file"></span><?php } else { ?><span class="fa fa-folder-open"></span><?php } ?> <?php echo $value['title'];?><span class="font-blue"><i>（<?php echo $value['identifier'];?>）</i></span>
                            </td>
                            <td><?php echo $value['taxis'];?></td>
                            <td class="text-center"><?php if($value['status']){ ?>启用<?php } else { ?><span class="font-red">禁用</span><?php } ?></td>
                            <td>
                                <?php if($popedom['add']){ ?>
                                <button type="button" onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'cate','func'=>'set','parent_id'=>$value['id']));?>')" class="btn btn-sm btn-warning" />
                                <i class="fa fa-plus"></i> 添加子分类
                                </button>
                                <?php } ?>
                                <?php if($popedom['modify']){ ?>
                                <button onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'cate','func'=>'set','id'=>$value['id']));?>')" class="btn btn-sm btn-success" />
                                <i class="fa fa-edit"></i> 编辑
                                </button>
                                <?php } ?>
                                <?php if($popedom['delete']){ ?>
                                <button onclick="cate_delete('<?php echo $value['id'];?>')" class="btn btn-sm btn-danger" />
                                <i class="fa fa-times"></i> 删除
                                </button>
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
<script type="text/javascript" src="<?php echo add_js('cate.js');?>"></script>
<script type="text/javascript">
    function cate_delete(id)
    {
        $.dialog.confirm("<?php echo P_Lang("确定要删除此分类吗？");?>",function(){
            var url = get_url("cate","delete");
            url += "&id="+id;
            var rs = $.dsy.json(url);
            if(rs.status == "ok"){
                $.dsy.reload();
            }else{
                $.dialog.alert(rs.content);
                return false;
            }
        });
    }

</script>
<?php $this->output("foot","file"); ?>
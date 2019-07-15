<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<script type="text/javascript" src="<?php echo add_js('stock.js');?>"></script>
<script type="text/javascript" src="<?php echo add_js('order.js');?>"></script>
<script type="text/javascript">
function zip_export(id){
    var url = get_url('batch','export','id='+id);
    $.dsy.go(url);
}
$(document).ready(function(){
     $("#stock_id").change(function(){
     var stock_id=$('#stock_id').val();
     var url = get_url('batch','index','stock_id='+stock_id);
     $.dsy.go(url);
     });
});
function importOrder(id)
{
    var url = get_url('batch','import','id='+id);
    var title = '批量上传订单';
    $.dialog.open(url,{
        'title':title,
        'width':'50%',
        'height':'70%',
        'lock':true
    });
}
</script>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('batch');?>" title="返回运单列表">批次管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>批次列表</span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="pull-right" >
                    <select id="stock_id" name="stock_id" class="form-control" style="width:200px; float:left;margin-right: 20px;">
                        <option value="">请选择所属仓库</option>
                        <?php $stocks_id["num"] = 0;$stocks=is_array($stocks) ? $stocks : array();$stocks_id["total"] = count($stocks);$stocks_id["index"] = -1;foreach($stocks AS $key=>$value){ $stocks_id["num"]++;$stocks_id["index"]++; ?>
                        <option value="<?php echo $value['id'];?>"<?php if($value['id'] == $stock_id){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                        <?php } ?>
                    </select>
                    <?php if($popedom['add']){ ?>
                    <a class="btn green" href="<?php echo admin_url('batch','set');?>">
                        <i class="fa fa-plus"></i>
                        添加批次
                    </a>
                    <?php } ?>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="bold">批次号</th>
                        <th class="bold">最新状态</th>
                        <th class="bold">所属仓库</th>
                        <th class="bold">创建时间</th>
                        <th class="bold text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td><a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'index','cid'=>$value['id']));?>"><?php echo $value['title'];?></a></td>
                        <td><?php echo $value['log']['note'];?></td>
                        <td><?php echo $value['stock']['title'];?></td>
                        <td><?php echo date('Y-m-d',$value['addtime']);?></td>
                        <td class="text-center">
                            <a class="btn btn-xs green-jungle" href="<?php echo admin_url('batch','out','cid='.$value['id']);?>">
                                <i class="fa fa-plus"></i> 扫描出库
                            </a>
                            <a class="btn btn-xs blue" href="javascript:void(0);" onclick="importOrder('<?php echo $value['id'];?>')">
                                <i class="fa fa-file-excel-o"></i>
                                导入订单
                            </a>
                            <?php if($popedom['delivery']){ ?>
                            <button type="button" onclick="order_batch('<?php echo $value['id'];?>','<?php echo $value['sign'];?>','<?php echo $value['title'];?>')" class="btn btn-xs yellow" />
                            <i class="fa fa-retweet"></i> 状态更新
                            </button>
                            <?php } ?>
                            <button type="button" onclick="zip_export('<?php echo $value['id'];?>')" class="btn btn-xs red" /><i class="icon-arrow-down "></i> 导出身份证
                            </button>
                            <div class="btn-group">
                                <a class="btn btn-xs green" href="javascript:;" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i> 批次操作
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php if($popedom['modify']){ ?>
                                    <li>
                                        <a href="javascript:;" onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'batch','func'=>'set','id'=>$value['id']));?>')">
                                            <i class="fa fa-edit"></i> 编辑 </a>
                                    </li>
                                    <?php } ?>
                                    <?php if($popedom['delete']){ ?>
                                    <li>
                                        <a href="javascript:;" onclick="batch_del('<?php echo $value['id'];?>','<?php echo $value['title'];?>')">
                                            <i class="fa fa-trash-o"></i> 删除 </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
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
<?php $this->output("foot","file"); ?>
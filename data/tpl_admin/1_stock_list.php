<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'stock'));?>">仓库管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>仓库列表</span>
        </li>
    </ul>
    <?php if($popedom['add']){ ?>
    <div class="page-toolbar" style="margin-top: 5px;margin-right: 10px;margin-bottom: 5px;">
        <div class="btn-group pull-right">
            <a class="btn green" href="<?php echo admin_url('stock','set');?>">
                <i class="fa fa-plus"></i>
                添加仓库
            </a>
        </div>
    </div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                        <tr>
                            <th class="bold">仓库名</th>
                            <th class="bold">地址</th>
                            <th class="bold">排序</th>
                            <th class="bold text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                        <tr>
                            <td><?php echo $value['title'];?></td>
                            <td><?php echo $value['address'];?>,　<?php echo $value['city'];?>　<?php echo $value['province'];?>　<?php echo $value['zipcode'];?></td>
                            <td><?php echo $value['taxis'];?></td>
                            <td class="text-center">
                                <?php if($popedom['modify']){ ?>
                                <a class="btn btn-xs btn-info" href="javascript:;" onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'stock','func'=>'set','id'=>$value['id']));?>')">
                                    <i class="fa fa-edit"></i>
                                    编辑
                                </a>
                                <?php } ?>
                                <?php if($popedom['delete']){ ?>
                                <a class="btn btn-xs btn-danger" href="javascript:;" onclick="stock_del('<?php echo $value['id'];?>','<?php echo $value['title'];?>')">
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
<script type="text/javascript" src="<?php echo add_js('stock.js');?>"></script>
<?php $this->output("foot","file"); ?>
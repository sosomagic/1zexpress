<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'custom'));?>">附加服务管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>附加服务列表</span>
        </li>
    </ul>
    <?php if($popedom['add']){ ?>
    <div class="pull-right" style="padding: 5px;">
        <a class="btn green" href="<?php echo admin_url('custom','set');?>">
            <i class="fa fa-plus"></i>
            添加服务
        </a>
    </div>
    <?php } ?>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="bold text-center">服务</th>
                        <th class="bold text-center">费用</th>
                        <th class="bold text-center">状态</th>
                        <th class="bold text-center">排序</th>
                        <th class="bold text-center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr>
                        <td class="text-center">
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                <input class="checkboxes" name="ids[]" value="<?php echo $value['id'];?>" type="checkbox">
                                <span></span>
                            </label>
                        </td>
                        <td class="text-center"><?php echo $value['title'];?></td>
                        <td class="text-center"><?php echo $value['price'];?></td>
                        <td class="text-center"><?php if($value['status']){ ?><label class="label label-sm bg-green-jungle">已启用</label><?php } else { ?><label class="label label-sm label-default">未启用</label><?php } ?></td>
                        <td class="text-center"><?php echo $value['taxis'];?></td>
                        <td class="text-center">
                            <button class="btn btn-xs btn-info" onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'custom','func'=>'set','id'=>$value['id']));?>')">
                                <i class="fa fa-edit"></i> 编辑
                            </button>
                            <button class="btn btn-xs btn-danger" onclick="custom_del('<?php echo $value['id'];?>','<?php echo $value['title'];?>')">
                                <i class="fa fa-times"></i> 删除
                            </button>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="form-inline">
                    <div class="form-group">
                        <input type="button" value="全选" class="btn btn-xs btn-danger" onclick="$.input.checkbox_all()" />
                        <input type="button" value="全不选" class="btn btn-xs btn-success" onclick="$.input.checkbox_none()" />
                        <input type="button" value="反选" class="btn btn-xs btn-warning" onclick="$.input.checkbox_anti()" />
                        <?php if($popedom['delete']){ ?><input type="button" value="删除" class="btn btn-xs red" onclick="set_delete()" /><?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo add_js('custom.js');?>"></script>
<?php $this->output("foot","file"); ?>
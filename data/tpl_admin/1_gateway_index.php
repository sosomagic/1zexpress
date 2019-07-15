<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'gateway'));?>">邮箱/短信</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>短信/邮箱设置</span>
        </li>
    </ul>
    <div class="text-right" style="padding: 5px 10px 5px 0"><a class="btn btn-circle green-meadow btn-outline sbold uppercase btn-sm" href="javascript:add_it('sms');void(0)"><?php echo P_Lang("添加短信接口");?></a>&nbsp;&nbsp;<a class="btn btn-circle green-meadow btn-outline sbold uppercase btn-sm" href="javascript:add_it('email');void(0)"><?php echo P_Lang("添加邮件接口");?></a></div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                        <tr>

                            <th class="col-md-4">标题</th>
                            <th class="col-md-4">所属网关</th>
                            <th class="col-md-1">默认</th>
                            <th class="col-md-3">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $tmpid["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$tmpid["total"] = count($rslist);$tmpid["index"] = -1;foreach($rslist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
                        <?php $value_list_id["num"] = 0;$value['list']=is_array($value['list']) ? $value['list'] : array();$value_list_id["total"] = count($value['list']);$value_list_id["index"] = -1;foreach($value['list'] AS $k=>$v){ $value_list_id["num"]++;$value_list_id["index"]++; ?>
                        <tr>
                            <td><?php echo $v['title'];?></td>
                            <td><?php echo $value['title'];?></td>
                            <td><?php if($v['is_default']){ ?>
                                是
                                <?php } else { ?>
                                <input type="button" value="设为默认" onclick="update_default(<?php echo $v['id'];?>)" class="btn btn-xs btn-default" />
                                <?php } ?></td>
                            <td>
                                <input type="button" value="<?php echo P_Lang("编辑");?>" onclick="$.dsy.go('<?php echo dsy_url(array('ctrl'=>'gateway','func'=>'set','id'=>$v['id']));?>')" class="btn btn-xs blue" />
                                <input type="button" value="<?php echo P_Lang("删除");?>" onclick="delete_it('<?php echo $v['id'];?>','<?php echo $v['title'];?>')" class="btn btn-xs btn-danger" />
                                <input type="button" value="<?php if($v['status']){ ?><?php echo P_Lang("启用中");?><?php } else { ?><?php echo P_Lang("已禁用");?><?php } ?>" onclick="update_status(<?php echo $v['id'];?>,<?php echo $v['status'];?>)" class="btn btn-xs btn-success" />
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo add_js('admin.gateway');?>"></script>
<?php $this->output("foot","file"); ?>
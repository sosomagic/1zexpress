<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('custom');?>" title="返回服务列表">服务管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span><?php if($id){ ?>编辑<?php } else { ?>添加<?php } ?>服务</span>
        </li>
    </ul>
</div>
<form method="post" action="<?php echo admin_url('custom','setok');?>" onsubmit="return check_save()">
    <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box grey">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right col-md-2">服务名称：</td>
                            <td><input id="title" name="title" type="text" value="<?php echo $rs['title'];?>" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right col-md-2">费用：</td>
                            <td><input id="price" name="price" type="text" value="<?php echo $rs['price'];?>" class="form-control"/>
                                <span class="help-block">0为免费，不收取费用</span>
                            </td>
                        </tr>
                        <!-- <tr>
                             <td class="text-right">数量：</td>
                             <td><input id="is_number" name="is_number" type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small" value="1"<?php if($rs['is_number']){ ?> checked<?php } ?>></td>
                         </tr>-->
                        <tr>
                            <td class="text-right">备注：</td>
                            <td><textarea name="note" maxlength="100" class="form-control" rows="2"><?php echo $rs['note'];?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">状态：</td>
                            <td><input id="status" name="status" type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small" value="1"<?php if($rs['status']){ ?> checked<?php } ?>></td>
                        </tr>
                        <tr>
                            <td class="text-right col-md-2">排序：</td>
                            <td><input id="taxis" name="taxis" value="<?php echo $rs['taxis'] ? $rs['taxis'] : 255;?>" class="form-control"/>
                                <span class="help-block">值越小越往前排，最大为255，最小为1</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button class="btn blue" type="submit"> 提 交 </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript" src="<?php echo add_js('custom.js');?>"></script>
<?php $this->output("foot","file"); ?>
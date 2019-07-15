<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('stock');?>" title="返回仓库列表">仓库管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span><?php if($id){ ?>编辑<?php } else { ?>添加<?php } ?>仓库</span>
        </li>
    </ul>
</div>
<form method="post" action="<?php echo admin_url('stock','setok');?>" onsubmit="return check_save()">
    <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box grey">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right col-md-2">仓库名：</td>
                            <td><input id="title" name="title" type="text" value="<?php echo $rs['title'];?>" class="form-control"/>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-right">州/省：</td>
                            <td><input id="province" name="province" type="text" class="form-control" value="<?php echo $rs['province'];?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">城市：</td>
                            <td><input id="city" name="city" type="text" class="form-control" value="<?php echo $rs['city'];?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">地址：</td>
                            <td><input id="address" name="address" type="text" class="form-control" value="<?php echo $rs['address'];?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">邮编：</td>
                            <td><input id="zipcode" name="zipcode" type="text" class="form-control" value="<?php echo $rs['zipcode'];?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">发货人：</td>
                            <td><input id="consignor" name="consignor" type="text" class="form-control" value="<?php echo $rs['consignor'];?>"/>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-right">电话：</td>
                            <td><input id="tel" name="tel" type="text" class="form-control" value="<?php echo $rs['tel'];?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">备注：</td>
                            <td><textarea name="note" maxlength="100" class="form-control" rows="2"><?php echo $rs['note'];?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">启用：</td>
                            <td><input id="status" name="status" type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small" value="1"<?php if($rs['status']){ ?> checked<?php } ?>></td>
                        </tr>
                        <tr>
                            <td class="text-right">排序：</td>
                            <td><input type="text" id="taxis" name="taxis" value="<?php echo $rs['taxis'] ? $rs['taxis'] : 255;?>" class="form-control">
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
<script type="text/javascript" src="<?php echo add_js('stock.js');?>"></script>
<?php $this->output("foot","file"); ?>
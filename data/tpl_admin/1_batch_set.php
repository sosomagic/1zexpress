<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('batch');?>" title="返回批次列表">批次管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span><?php if($id){ ?>编辑<?php } else { ?>添加<?php } ?>批次</span>
        </li>
    </ul>
</div>
<form method="post" action="<?php echo admin_url('batch','setok');?>">
    <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right col-md-2">批次名：</td>
                            <td><input id="title" name="title" type="text" value="<?php echo $rs['title'];?>" class="form-control"/></td>
                        </tr>
                        <tr>
                            <td class="text-right"><span class="font-red"> * </span>仓库：</td>
                            <td>
                                <select class="form-control" id="stock" name="stock">
                                    <option value="">所属仓库</option>
                                    <?php $stock_id["num"] = 0;$stock=is_array($stock) ? $stock : array();$stock_id["total"] = count($stock);$stock_id["index"] = -1;foreach($stock AS $key=>$value){ $stock_id["num"]++;$stock_id["index"]++; ?>
                                    <option value="<?php echo $value['id'];?>"<?php if($rs['stock_id']==$value['id']){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">备注：</td>
                            <td><textarea name="note" maxlength="100" class="form-control" rows="2"><?php echo $rs['note'];?></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button class="btn blue" type="submit">提 交</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript" src="<?php echo add_js('channel.js');?>"></script>
<?php $this->output("foot","file"); ?>
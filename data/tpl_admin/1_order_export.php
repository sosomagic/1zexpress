<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('order');?>" title="返回运单列表">运单管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>批量导出运单</span>
        </li>
    </ul>
</div>
<form method="post" action="<?php echo admin_url('order','export_data');?>">
<div class="row">
<div class="col-md-12">
<div class="portlet light bordered">
<div class="portlet-body">
<table class="table table-striped table-bordered table-hover">
<tbody>
<tr>
    <td>
        <div class="md-checkbox-inline">
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_sn" value="sn" checked />
                <label for="ext_sn">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 订单号 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_express_sn" value="express_sn" checked />
                <label for="ext_express_sn">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 转运单号 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_addtime" value="addtime" checked />
                <label for="ext_addtime">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 创建时间 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_idcard" value="idcard" checked />
                <label for="ext_idcard">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 身份证号 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_catename" value="catename" checked/>
                <label for="ext_catename">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 货物品类 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_consignor" value="consignor" checked />
                <label for="ext_consignor">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 发件人姓名 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_consignor_tel" value="consignor_tel" checked />
                <label for="ext_consignor_tel">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 发件人电话 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_fullname" value="fullname" checked />
                <label for="ext_fullname">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 收件人姓名 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_mobile" value="mobile" checked />
                <label for="ext_mobile">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 收件人电话 </label>
            </div>
        </div>
    </td>
</tr>
<tr>
    <td>
        <div class="md-checkbox-inline">
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_address" value="address" checked />
                <label for="ext_address">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 收件人地址 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_packages" value="packages" checked />
                <label for="ext_packages">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 内件汇总 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_idcardStatus" value="idcardStatus" checked />
                <label for="ext_idcardStatus">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 身份证状态 </label>
            </div>

            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_status" value="status" checked />
                <label for="ext_status">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 包裹状态 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_jingzhong" value="jingzhong" checked />
                <label for="ext_jingzhong">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 包裹重量 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_pay_weight" value="pay_weight" checked  />
                <label for="ext_pay_weight">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 仓库复重 </label>
            </div>
            <div class="md-checkbox">
                <input type="checkbox" name="ext[]" id="ext_batchNo" value="batchNo" checked />
                <label for="ext_batchNo">
                    <span></span>
                    <span class="check"></span>
                    <span class="box" style="width: 17px;height: 17px;border:1px solid #666"></span> 提单号 </label>
            </div>
        </div>
    </td>
</tr>
</tbody>
</table>
<div class="row">
    <div class="form-group col-md-2">
        <select name="status" class="form-control">
            <option value="">全部运单</option>
            <?php $statuslist_id["num"] = 0;$statuslist=is_array($statuslist) ? $statuslist : array();$statuslist_id["total"] = count($statuslist);$statuslist_id["index"] = -1;foreach($statuslist AS $key=>$value){ $statuslist_id["num"]++;$statuslist_id["index"]++; ?>
            <option value="<?php echo $key;?>"><?php echo $value;?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-2">
        <select id="stock_id" name="stock_id" class="form-control">
            <option value="">仓库</option>
            <?php $stocks_id["num"] = 0;$stocks=is_array($stocks) ? $stocks : array();$stocks_id["total"] = count($stocks);$stocks_id["index"] = -1;foreach($stocks AS $key=>$value){ $stocks_id["num"]++;$stocks_id["index"]++; ?>
            <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-2">
        <select id="channel_id" name="channel_id" class="form-control">
            <option value="">发货渠道</option>
            <?php $channel_list_id["num"] = 0;$channel_list=is_array($channel_list) ? $channel_list : array();$channel_list_id["total"] = count($channel_list);$channel_list_id["index"] = -1;foreach($channel_list AS $key=>$value){ $channel_list_id["num"]++;$channel_list_id["index"]++; ?>
            <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-2">
        <select class="form-control" name="batch">
            <option value="">清关批次</option>
            <?php $batch_id["num"] = 0;$batch=is_array($batch) ? $batch : array();$batch_id["total"] = count($batch);$batch_id["index"] = -1;foreach($batch AS $key=>$value){ $batch_id["num"]++;$batch_id["index"]++; ?>
            <option value="<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-2">
        <input class="form-control" type="text" name="sn" value="<?php echo $sn;?>" placeholder="运单号" />
    </div>
    <div class="form-group col-md-2">
        <input id="dateRange" class="form-control" type="text" name="dateRange" value="" placeholder="日期范围"/>
    </div>
    <div class="form-group col-md-2">
        <input class="form-control" type="text" name="ucode" value="" placeholder="入库标识码" />
    </div>
    <div class="form-group col-md-2">
        <input class="form-control input-small" type="text" name="user" value="" placeholder="会员名" />
    </div>
    <div class="form-group col-md-2">
        <input class="form-control input-small" type="text" name="consignee" value="" placeholder="收件人" />
    </div>
    <div class="form-group col-md-2">
        <input class="form-control input-small" type="text" name="consignee_mobile" value="" placeholder="收件人手机" />
    </div>

</div>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12 text-center">
        <button class="btn blue" type="submit">
            <i class="fa fa-edit"></i>
            批量导出运单
        </button>
    </div>
</div>
</div>
</div>
</div>
</div>
</form>
<script type="text/javascript" src="js/laydate/laydate.js"></script>
<script>
	//日期范围
    laydate.render({
        elem: '#dateRange'
        ,range: true
    });
</script>
<?php $this->output("foot","file"); ?>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'order'));?>" title="返回运单列表">运单管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>运单详细信息</span>
        </li>
    </ul>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <div class="portlet box grey table-scrollable">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td class="text-right col-md-2">运单号：</td>
                        <td><?php echo $rs['sn'];?>(<?php echo $log['note'];?>)</td>
                        <td class="text-right col-md-2">下单时间：</td>
                        <td><?php echo date('Y-m-d H:i:s',$rs['addtime']);?></td>
                    </tr>
                    <tr>
                        <td class="text-right col-md-2">发货渠道：</td>
                        <td><?php echo $rs['channel']['title'];?></td>
                        <td class="text-right col-md-2">发货仓库：</td>
                        <td><?php echo $rs['stock']['title'];?></td>
                    </tr>
                    <tr>
                        <td class="text-right">会员名：</td>
                        <td><?php echo $user['user'];?></td>
                        <td class="text-right">会员编号：</td>
                        <td><?php echo $user['ucode'];?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey table-scrollable">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bars"></i>发件人信息</div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td class="text-right col-md-2">发件人姓名：</td>
                        <td><?php echo $rs['consignor'];?></td>
                        <td class="text-right col-md-2">发件人电话：</td>
                        <td><?php echo $rs['consignor_tel'];?></td>
                    </tr>
                    <tr>
                        <td class="text-right">发件人地址：</td>
                        <td colspan="3"><?php echo $rs['consignor_address'];?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey table-scrollable">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bars"></i>收件人信息</div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td class="text-right col-md-2">收件人姓名：</td>
                        <td><?php echo $rs['address']['fullname'];?></td>
                        <td class="text-right">收件人手机：</td>
                        <td><?php echo $rs['address']['mobile'];?></td>
                    </tr>
                    <tr>
                        <td class="text-right col-md-2">收件人地址：</td>
                        <td colspan="3"><?php echo $rs['address']['province'];?>
                            <?php if($rs['address']['province'] != $rs['address']['city']){ ?>
                            <?php echo $rs['address']['city'];?>
                            <?php } ?>
                            <?php echo $rs['address']['county'];?>
                            <?php echo $rs['address']['address'];?> <?php echo $rs['address']['zipcode'];?></td>
                    </tr>
                    <tr>
                        <td class="text-right col-md-2">收件人身份证号：</td>
                        <td><?php echo $rs['address']['idcard'];?></td>
                        <td class="text-right col-md-2">收件人身份照片：</td>
                        <td>
                            <?php if($rs['address']['idcard_front']<>''){ ?><a href="javascript:address_idcard('<?php echo $rs['address']['idcard_front'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-正面"></a><?php } ?>&nbsp;&nbsp;<?php if($rs['address']['idcard_back']<>''){ ?><a href="javascript:address_idcard('<?php echo $rs['address']['idcard_back'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-反面"></a><?php } else { ?><span class="font-red">无身份证照片</span> <?php } ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bars"></i>物品信息</div>
                <div class="tools">
                    <a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="bold">类别</th>
                        <th class="bold">品牌</th>
                        <th class="bold">物品名称</th>
                        <th class="bold">规格</th>
                        <th class="bold">数量</th>
                        <th class="bold">价格</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $tmpid["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$tmpid["total"] = count($rslist);$tmpid["index"] = -1;foreach($rslist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
                    <tr>
                        <td><?php echo $value['catename'];?></td>
                        <td><?php echo $value['brand'];?></td>
                        <td><?php echo $value['title'];?></td>
                        <td><?php echo $value['size'];?></td>
                        <td><?php echo $value['qty'];?></td>
                        <td><?php echo $value['price'];?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="clearfix"></div>
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td class="text-right col-md-2">申报价值：</td>
                        <td><?php echo $rs['val'];?> <?php echo $rs['currency']['title'];?></td>
                        <td class="text-right col-md-2">税     费：</td>
                        <td><?php echo $rs['tax'];?> <?php echo $rs['currency']['title'];?></td>
                    </tr>
                    <tr>
                        <td class="text-right col-md-2">物品保价：</td>
                        <td><?php echo $rs['safe'];?> <?php echo $rs['currency']['title'];?></td>
                        <td class="text-right col-md-2">保价费用：</td>
                        <td><?php echo $rs['safe_price'];?> <?php echo $rs['currency']['title'];?></td>
                    </tr>
                    <tr>
                        <td class="text-right col-md-2">预估重量：</td>
                        <td><?php echo $rs['weight'];?> <?php echo $rs['weight_id'];?></td>
                        <td class="text-right col-md-2">实际重量：</td>
                        <td><?php echo $rs['jingzhong'];?> <?php echo $rs['weight_id'];?></td>
                    </tr>
                    <tr>
                        <td class="text-right col-md-2">增值服务：</td>
                        <td><?php $custom_temp_id["num"] = 0;$custom_temp=is_array($custom_temp) ? $custom_temp : array();$custom_temp_id["total"] = count($custom_temp);$custom_temp_id["index"] = -1;foreach($custom_temp AS $key=>$value){ $custom_temp_id["num"]++;$custom_temp_id["index"]++; ?><?php echo $custom[$key]['title'];?>（<?php echo $custom[$key]['price'];?><?php echo $rs['currency']['title'];?>）<?php } ?>
                        </td>
                        <td class="text-right col-md-2">运单备注：</td>
                        <td><?php echo nl2br($rs['note']);?></td>
                    </tr>
                    <tr>
                        <td class="text-right col-md-2">计费重量：</td>
                        <td><?php echo $rs['pay_weight'];?> <?php echo $rs['weight_id'];?></td>
                        <td class="text-right col-md-2">总费用：</td>
                        <td><?php echo $rs['price'];?> <?php echo $rs['currency']['title'];?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php if($package){ ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bars"></i>包裹信息</div>
                <div class="tools">
                    <a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="bold">包裹号</th>
                        <th class="bold">仓库</th>
                        <th class="bold">仓位</th>
                        <th class="bold">快递公司</th>
                        <th class="bold">重量</th>
                        <th class="bold">入库日期</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $package_id["num"] = 0;$package=is_array($package) ? $package : array();$package_id["total"] = count($package);$package_id["index"] = -1;foreach($package AS $key=>$value){ $package_id["num"]++;$package_id["index"]++; ?>
                    <tr>
                        <td><?php echo $value['sn'];?></td>
                        <td><?php echo $value['stock_title']['title'];?></td>
                        <td><?php echo $value['position'];?></td>
                        <td><?php echo $value['express'];?></td>
                        <td><?php echo $value['jingzhong'];?></td>
                        <td><?php if($value['rukutime']){ ?><?php echo date('Y-m-d H:i',$value['rukutime']);?><?php } else { ?>---<?php } ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<script type="text/javascript">
    function address_idcard(id)
    {
        var url = get_url('user','address_idcard','id='+id);
        var title = '身份证照片';
        $.dialog.open(url,{
            'title':title,
            'lock':true,
            'width':'318px',
            'height':'438px',
            'cancel':function(){
                return true;
            }
        });
    }
</script>
<?php $this->output("foot","file"); ?>
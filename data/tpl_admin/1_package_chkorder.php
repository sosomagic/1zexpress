<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'package'));?>">转运管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>待审核运单列表</span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="navbar-collapse bg-white">
            <form class="navbar-form navbar-left" method="post" action="<?php echo admin_url('package','chkorder');?>">
                <div class="row">
                    <div class="form-group">
                        <select id="stock_id" name="stock_id" class="form-control" style="width:200px;">
                            <option value="">仓库</option>
                            <?php $stocks_id["num"] = 0;$stocks=is_array($stocks) ? $stocks : array();$stocks_id["total"] = count($stocks);$stocks_id["index"] = -1;foreach($stocks AS $key=>$value){ $stocks_id["num"]++;$stocks_id["index"]++; ?>
                            <option value="<?php echo $value['id'];?>"<?php if($value['id'] == $stock_id){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select id="channel_id" name="channel_id" class="form-control" style="width:150px;">
                            <option value="">发货渠道</option>
                            <?php $channel_list_id["num"] = 0;$channel_list=is_array($channel_list) ? $channel_list : array();$channel_list_id["total"] = count($channel_list);$channel_list_id["index"] = -1;foreach($channel_list AS $key=>$value){ $channel_list_id["num"]++;$channel_list_id["index"]++; ?>
                            <option value="<?php echo $value['id'];?>"<?php if($value['id'] == $channel_id){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input id="dateRange" class="form-control" type="text" name="dateRange" value="<?php echo $dateRange;?>" placeholder="下单日期范围"/>
                    </div>
                    <div class="form-group">
                        <input class="form-control input-small" type="text" name="sn" value="<?php echo $sn;?>" placeholder="运单号" />
                    </div>
                    <div class="form-group">
                        <input class="form-control input-small" type="text" name="consignor" value="<?php echo $consignor;?>" placeholder="发件人" />
                    </div>
                    <div class="form-group">
                        <input class="form-control input-small" type="text" name="ucode" value="<?php echo $ucode;?>" placeholder="会员编号" />
                    </div>
                    <div class="form-group">
                        <input class="form-control input-small" type="text" name="user" value="<?php echo $user;?>" placeholder="会员名" />
                    </div>
                    <div class="form-group">
                        <input class="form-control input-small" type="text" name="consignee" value="<?php echo $consignee;?>" placeholder="收件人" />
                    </div>
                    <div class="form-group">
                        <input class="form-control input-small" type="text" name="consignee_mobile" value="<?php echo $consignee_mobile;?>" placeholder="收件人手机" />
                    </div>
                    <div class="form-group">
                        <button class="btn blue" type="submit"><i class="fa fa-search"></i> 查 询 </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <div class="tabbable tabbable-tabdrop">
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                    <tr>
                                        <th class="bold">&nbsp;</th>
                                        <th class="bold text-center">运单号</th>
                                        <th class="bold text-center">包裹号</th>
                                        <th class="bold text-center">会员</th>
                                        <th class="bold text-center">仓库/渠道</th>
                                        <th class="bold text-center">收件人/身份证</th>
                                        <th class="bold text-center">实重(LB)</th>
                                        <th class="bold text-center">运费($)</th>
                                        <th class="bold text-center">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                                    <tr>
                                        <td class="text-center">
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input class="checkboxes" id="id_<?php echo $value['id'];?>" name="ids[]" value="<?php echo $value['id'];?>" type="checkbox">
                                                <span></span>
                                            </label>
                                        </td>
                                        <td class="text-center"><a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'info','id'=>$value['id']));?>"><?php echo $value['sn'];?></a><br><a onclick="showtr('trshow<?php echo $key+1;?>','imgsrc<?php echo $key+1;?>');"><img id="imgsrc<?php echo $key+1;?>" src="tpl/www/images/arrow_down1.png"></a></td>
                                        <td class="text-center">
                                            <?php $value_package_id["num"] = 0;$value['package']=is_array($value['package']) ? $value['package'] : array();$value_package_id["total"] = count($value['package']);$value_package_id["index"] = -1;foreach($value['package'] AS $k=>$v){ $value_package_id["num"]++;$value_package_id["index"]++; ?>
                                            <?php echo $v['sn'];?>[<?php echo $v['position'];?>]<br>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center"><?php if($value['user']){ ?><a href="javascript:;" onclick="showuser('<?php echo $value['user_id'];?>')"> <?php echo $value['user'];?><br>（<?php echo $value['ucode'];?>）</a><?php } else { ?>游客<?php } ?></td>
                                        <td class="text-center"><?php echo $value['stock']['title'];?><br><?php echo $channel[$key]['title'];?></td>
                                        <td class="text-center"><?php echo $address[$key]['fullname'];?><br><?php if($address[$key]['idcard_front']<>''){ ?><a href="javascript:address_idcard('<?php echo $address[$key]['idcard_front'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-正面"></a><?php } ?><?php if($address[$key]['idcard_back']<>''){ ?><a href="javascript:address_idcard('<?php echo $address[$key]['idcard_back'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-反面"></a><?php } ?></td>
                                        <td class="text-center"><?php echo $value['jingzhong'];?></td>
                                        <td class="text-center"><?php echo $value['price'];?></td>
                                        <td class="text-center">
                                            <input type="button" value="扣款" onclick="order_pay('<?php echo $value['id'];?>','<?php echo $pageurl;?>')" class="btn btn-xs btn-success" />
                                            <?php if($popedom['delete']){ ?>
                                            <input type="button" value="删除" onclick="chkorder_delete('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs btn-danger" />
                                            <?php } ?>
                                            <?php if($value['status']!='unpaid'){ ?>
                                            <input type="button" value="打印" onclick="back_print('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs btn-warning" />
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr id="trshow<?php echo $key+1;?>" style="display:none">
                                        <td colspan="9" align="left">
                                            <div class="modal_border">
                                                总费用：<?php echo $value['price'];?><?php echo $value['currency']['title'];?>(<?php echo price_format($value['price'],$value['currency_id'],$config['currency_id']);?>) | 价值：<?php echo $value['val'];?><?php echo $value['currency']['title'];?> | 税费：<?php echo $value['tax'];?><?php echo $value['currency']['title'];?> | 保价：<?php echo $value['safe'];?><?php echo $value['currency']['title'];?> | 保费：<?php echo $value['safe_price'];?><?php echo $value['currency']['title'];?> | 增值服务费：<?php echo $value['custom_price'];?><?php echo $value['currency']['title'];?> | 下单时间：<?php echo date('Y-m-d H:i:s',$value['addtime']);?>
                                            </div>
                                            <div class="modal_border">
                                                预估重量：<?php echo $value['weight'];?><?php echo $value['weight_id'];?><?php if(!$value['jingzhong'] || $value['jingzhong']!='0.00'){ ?>　|　称重重量：<?php echo $value['jingzhong'];?><?php echo $value['weight_id'];?><?php } ?><?php if($value['volume']){ ?>　|　体积重量：<?php echo $value['volume'];?><?php echo $value['weight_id'];?>(<?php echo $value['length'];?>*<?php echo $value['width'];?>*<?php echo $value['height'];?>)<?php } ?><?php if(!$value['pay_weight'] || $value['pay_weight']!='0.00'){ ?>　|　计费重量：<?php echo $value['pay_weight'];?><?php echo $value['weight_id'];?><?php } ?>　|　预估费用：<?php echo $value['channel_price'];?><?php echo $value['currency']['title'];?>　|　增值服务：
                                                <?php $list_id["num"] = 0;$value['custom']=is_array($value['custom']) ? $value['custom'] : array();$list_id["total"] = count($value['custom']);$list_id["index"] = -1;foreach($value['custom'] AS $k=>$v){ $list_id["num"]++;$list_id["index"]++; ?>
                                                <?php echo $v['title'];?><?php if($list_id['num'] != $list_id['total']){ ?> | <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <div class="modal_border">
                                                <strong>货物：</strong>
                                                <?php if($value['pros']){ ?>
                                                <?php $list_id["num"] = 0;$value['pros']=is_array($value['pros']) ? $value['pros'] : array();$list_id["total"] = count($value['pros']);$list_id["index"] = -1;foreach($value['pros'] AS $k=>$v){ $list_id["num"]++;$list_id["index"]++; ?>
                                                <?php echo $v['brand'];?><?php echo $v['title'];?>X<?php echo $v['qty'];?><?php if($list_id['num'] != $list_id['total']){ ?>　|　<?php } ?></li>
                                                <?php } ?>
                                                <?php } ?>
                                                <strong>备注：</strong>
                                                <?php echo $value['note'];?>
                                            </div>
                                            <div class="modal_border">
                                                <strong>包裹信息：</strong>
                                                <?php $value_package_id["num"] = 0;$value['package']=is_array($value['package']) ? $value['package'] : array();$value_package_id["total"] = count($value['package']);$value_package_id["index"] = -1;foreach($value['package'] AS $k=>$v){ $value_package_id["num"]++;$value_package_id["index"]++; ?>
                                                包裹号：<?php echo $v['sn'];?>　     仓位：<?php echo $v['position'];?>　   入库时间：<?php echo date('Y-m-d H:i:s',$v['rukutime']);?>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-inline">
                                <div class="form-group">
                                    <input type="button" value="全选" class="btn btn-xs btn-danger" onclick="$.input.checkbox_all()" />
                                    <input type="button" value="全不选" class="btn btn-xs btn-success" onclick="$.input.checkbox_none()" />
                                    <input type="button" value="反选" class="btn btn-xs btn-warning" onclick="$.input.checkbox_anti()" />
                                </div>
                                <a class="btn btn-xs blue" href="javascript:void(0);" onclick="order_pldy()">
                                    <i class="fa fa-print"></i>
                                    批量打印
                                </a>
                            </div>
                            <div class="col-md-6 text-right"><?php $this->output("pagelist","file"); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo add_js('order.js');?>"></script>
<script type="text/javascript" src="js/laydate/laydate.js"></script>
<script type="text/javascript" src="js/LodopFuncs.js"></script>
<script type="text/javascript" src="<?php echo add_js('print.js');?>"></script>
<script type="text/javascript">
	//日期范围
    laydate.render({
        elem: '#dateRange'
        ,range: true
    });
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

    function showtr(trshow,imgsrc){

        var trid=$('#'+trshow);

        var imgid=$('#'+imgsrc);

        if(trid.is(":hidden")){

            trid.show();    //如果元素为隐藏,则将它显现

            imgid.attr("src", "tpl/www/images/arrow_up.png");

        }else{

            trid.hide();     //如果元素为显现,则将其隐藏

            imgid.attr("src", "tpl/www/images/arrow_down1.png");

        }

    }

    function showuser(id)

    {

        var url = get_url('user','show','id='+id);

        $.dialog.open(url,{

            'title':'用户详细信息',

            'width':'50%',

            'height':'50%',

            'lock':true

        });

    }
</script>
<?php $this->output("foot","file"); ?>
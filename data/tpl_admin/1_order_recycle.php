<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'order'));?>">运单管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>运单回收站</span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="navbar-collapse bg-white">
            <form class="navbar-form navbar-left" method="post" action="<?php echo admin_url('order','recycle');?>">
                <div class="row">
                    <div class="form-group">
                        <select id="stock_id" name="stock_id" class="form-control">
                            <option value="">仓库</option>
                            <?php $stocks_id["num"] = 0;$stocks=is_array($stocks) ? $stocks : array();$stocks_id["total"] = count($stocks);$stocks_id["index"] = -1;foreach($stocks AS $key=>$value){ $stocks_id["num"]++;$stocks_id["index"]++; ?>
                            <option value="<?php echo $value['id'];?>"<?php if($value['id'] == $stock_id){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select id="channel_id" name="channel_id" class="form-control">
                            <option value="">发货渠道</option>
                            <?php $channel_list_id["num"] = 0;$channel_list=is_array($channel_list) ? $channel_list : array();$channel_list_id["total"] = count($channel_list);$channel_list_id["index"] = -1;foreach($channel_list AS $key=>$value){ $channel_list_id["num"]++;$channel_list_id["index"]++; ?>
                            <option value="<?php echo $value['id'];?>"<?php if($value['id'] == $channel_id){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input class="form-control input-small" type="text" name="batch" value="<?php echo $batch;?>" placeholder="清关批次" />
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
                        <input class="form-control input-small" type="text" name="ucode" value="<?php echo $ucode;?>" placeholder="入库标识码" />
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
                        <button class="btn btn-info" type="submit"><i class="fa fa-search"></i> 查 询 </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="tabbable tabbable-tabdrop">
                    <div class="tab-content">
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <thead>
                            <tr>
                                <th class="bold">&nbsp;</th>
                                <th class="bold text-center">运单号</th>
                                <th class="bold text-center">会员</th>
                                <th class="bold text-center">渠道</th>
                                <th class="bold text-center">清关批次</th>
                                <th class="bold text-center">仓库/仓位</th>
                                <th class="bold text-center">收件人</th>
                                <th class="bold text-center">状态</th>
                                <th class="bold text-center">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                            <tr onclick="TestBlack('trshow<?php echo $key+1;?>');">
                                <td class="text-center">
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input class="checkboxes" id="id_<?php echo $value['id'];?>" name="ids[]" value="<?php echo $value['id'];?>" type="checkbox">
                                        <span></span>
                                    </label>

                                </td>
                                <td class="text-center"><a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'info','id'=>$value['id']));?>"><?php echo $value['sn'];?></a></td>
                                <td class="text-center"><?php echo $value['user'];?><br><span class="font-blue-oleo"><?php echo $value['ucode'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $value['user_id'];?></span></td>
                                <td class="text-center"><?php echo $channel[$key]['title'];?></td>
                                <td class="text-center font-blue"><?php echo $value['batch']['title'];?></td>
                                <td class="text-center"><?php echo $value['stock']['title'];?><br><span class="font-blue-oleo"><?php echo $value['position'];?></span></td>
                                <td class="text-center"><?php echo $address[$key]['fullname'];?><br><span class="font-blue-oleo"><?php echo $address[$key]['mobile'];?></span></td>
                                <td class="text-center"><?php echo $value['track']['note'] ? $value['track']['note'] : $value['ext'];?></td>
                                <td class="text-center">
                                    <input type="button" value="还原" onclick="order_renew('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs btn-info" />
                                    <?php if($popedom['delete']){ ?>
                                    <input type="button" value="删除" onclick="order_delete('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs btn-danger" />
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr id="trshow<?php echo $key+1;?>" style="display:<?php echo $key+1 > 1 ? none: '';?>">
                                <td colspan="9" align="left">
                                    <div class="modal_border">
                                        预估重量：<?php echo $value['weight'];?><?php echo $value['weight_units'][$value['weight_id']];?><?php if(!$value['jingzhong'] || $value['jingzhong']!='0.00'){ ?> | 称重重量：<?php echo $value['jingzhong'];?><?php echo $value['weight_units'][$value['weight_id']];?><?php } ?><?php if(!$value['pay_weight'] || $value['pay_weight']!='0.00'){ ?> | 计费重量：<?php echo $value['pay_weight'];?><?php echo $value['weight_units'][$value['weight_id']];?><?php } ?> | 费用：<?php echo $value['price'];?><?php echo $value['currency']['title'];?>(<?php echo price_format($value['price'],$value['currency_id'],$config['currency_id']);?>) | 价值：<?php echo $value['val'];?><?php echo $value['currency']['title'];?> | 税费：<?php echo $value['tax'];?><?php echo $value['currency']['title'];?> | 保价：<?php echo $value['safe'];?><?php echo $value['currency']['title'];?> | 保费：<?php echo $value['safe_price'];?><?php echo $value['currency']['title'];?>
                                    </div>

                                    <div class="modal_border">
                                        仓库：<?php echo $value['stock']['title'];?><?php if($value['position']<>''){ ?>（<?php echo $value['position'];?>）<?php } ?> | 来源：<?php echo $from[$value['type']];?><?php if($value['batch']['title']){ ?> | 清关批次：<?php } ?><?php echo $value['batch']['title'];?> <?php if($value['express_sn']){ ?>| 快递单号：<?php echo $value['express_sn'];?>（<?php echo $value['express'];?>） <?php } ?>| 下单时间：<?php echo date('Y-m-d H:i:s',$value['addtime']);?> | 身份证：<?php echo $address[$key]['idcard'];?> <?php if($address[$key]['idcard_front']<>''){ ?><a href="javascript:address_idcard('<?php echo $address[$key]['idcard_front'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-正面"></a><?php } ?>
                                        <?php if($address[$key]['idcard_back']<>''){ ?><a href="javascript:address_idcard('<?php echo $address[$key]['idcard_back'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-反面"></a><?php } ?>
                                    </div>
                                    <div class="modal_border">
                                        <strong>货物：</strong>
                                        <?php if($value['pros']){ ?>
                                        <?php $value_pros_id["num"] = 0;$value['pros']=is_array($value['pros']) ? $value['pros'] : array();$value_pros_id["total"] = count($value['pros']);$value_pros_id["index"] = -1;foreach($value['pros'] AS $k=>$v){ $value_pros_id["num"]++;$value_pros_id["index"]++; ?>
                                        <?php echo $v['brand'];?><?php echo $v['title'];?>X<?php echo $v['qty'];?>；
                                        <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <?php if($value['note']){ ?>
                                    <div class="modal_border">
                                        <strong>备注：</strong>
                                        <?php echo $value['note'];?>
                                    </div>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6 form-inline">
                                <div class="form-group">
                                    <input type="button" value="全选" class="btn btn-xs btn-danger" onclick="$.input.checkbox_all()" />
                                    <input type="button" value="全不选" class="btn btn-xs btn-success" onclick="$.input.checkbox_none()" />
                                    <input type="button" value="反选" class="btn btn-xs btn-warning" onclick="$.input.checkbox_anti()" />
                                </div>
                                <a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="order_deletes()">
                                    <i class="fa fa-close"></i>
                                    批量删除
                                </a>
                            </div>
                            <div class="col-md-6 text-right" style="font-size: 12px;"><?php $this->output("pagelist","file"); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo add_js('order.js');?>"></script>
<script type="text/javascript" src="js/laydate/laydate.js"></script>
<script type="text/javascript">
    //日期范围
    laydate.render({
        elem: '#dateRange'
        ,range: true
    });
    function TestBlack(TagName)
    {
        //先全部隐藏
        for (var i=1;i<1000;i++)
        {
            if(document.getElementById('trshow'+i)!=undefined)
            {
                document.getElementById('trshow'+i).style.display = "none";//alert("存在");
            }else{
                break;
            }
        }
        //处理
        var obj = document.getElementById(TagName);
        if(obj.style.display==""){
            obj.style.display = "none";

        }else{
            obj.style.display = "";

        }
    }
</script>
<?php $this->output("foot","file"); ?>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'order'));?>">运单管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>运单列表</span>
        </li>
    </ul>
</div>
<div class="page-bar" style="padding-left:8px;padding-right:8px;">
    <form id="search" class="navbar-form navbar-left" method="post" action="<?php echo admin_url('order');?>">
        <div class="row">
            <div class="form-group">
                <select id="status" name="status" class="form-control input-small">
                    <option value="">全部运单</option>
                    <?php $statuslist_id["num"] = 0;$statuslist=is_array($statuslist) ? $statuslist : array();$statuslist_id["total"] = count($statuslist);$statuslist_id["index"] = -1;foreach($statuslist AS $key=>$value){ $statuslist_id["num"]++;$statuslist_id["index"]++; ?>
                    <option value="<?php echo $key;?>"<?php if($key == $status){ ?> selected<?php } ?>><?php echo $value;?></option>
                    <?php } ?>
                </select>
            </div>
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
                <input class="form-control input-small" type="text" name="batch" value="<?php echo $batch;?>" placeholder="清关批次" />
            </div>
            <div class="form-group">
                <input id="dateRange" class="form-control" type="text" name="dateRange" value="<?php echo $dateRange;?>" placeholder="下单日期范围"/>
            </div>
            <div class="form-group">
                <input class="form-control input-xsmall" type="text" name="express_sn" value="<?php echo $express_sn;?>" placeholder="国内单号" />
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
<div class="row">
<div class="col-md-12">
<div class="portlet light bordered">
<div class="portlet-body">
<div class="note note-info form-inline text-right">
    <div class="form-group">
        <input type="button" value="全选" class="btn btn-xs btn-danger" onclick="$.input.checkbox_all()" />
        <input type="button" value="全不选" class="btn btn-xs btn-success" onclick="$.input.checkbox_none()" />
        <input type="button" value="反选" class="btn btn-xs btn-warning" onclick="$.input.checkbox_anti()" />
    </div>
    <a class="btn btn-xs blue" href="javascript:void(0);" onclick="order_weight(0)">导入重量</a>
    <a class="btn btn-xs green" href="javascript:void(0);" onclick="order_pays()">批量扣款</a>
    <a class="btn btn-xs btn-warning" href="javascript:void(0);" onclick="order_pldy()">批量打印</a>
    <a class="btn btn-xs yellow" href="javascript:void(0);" onclick="order_charged(0)">扫描扣款</a>
    <a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="removes()">批量删除</a>
</div>
<div class="tabbable-line">
    <ul class="nav nav-tabs">
        <li class="<?php if($status==''){ ?>active <?php } ?>bold">
            <a href="javascript:void(0)" onclick="tab('')">全部运单<span class="badge badge-success"><?php echo $sum;?></span></a>
        </li>
        <?php $statuslist_id["num"] = 0;$statuslist=is_array($statuslist) ? $statuslist : array();$statuslist_id["total"] = count($statuslist);$statuslist_id["index"] = -1;foreach($statuslist AS $key=>$value){ $statuslist_id["num"]++;$statuslist_id["index"]++; ?>
        <li class="<?php if($status==$key){ ?>active <?php } ?>bold">
            <a href="javascript:void(0)" onclick="tab('<?php echo $key;?>')"><?php echo $value;?><span class="badge badge-success"> <?php echo $count[$key];?></span></a>
        </li>
        <?php } ?>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <div class="table-scrollable">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th class="bold">&nbsp;</th>
                        <th class="bold text-center">序号</th>
                        <th class="bold text-center">运单号</th>
                        <th class="bold text-center">会员</th>
                        <th class="bold text-center">仓库/渠道</th>
                        <th class="bold text-center">清关批次</th>
                        <th class="bold text-center">重量(LB)</th>
                        <th class="bold text-center">总费用($)</th>
                        <th class="bold text-center">发件人</th>
                        <th class="bold text-center">收件人</th>
                        <th class="bold text-center">身份证</th>
                        <th class="bold text-center">状态</th>
                        <th class="bold text-center">
                            <?php if($status == 'paid'){ ?>
                            扣款时间
                            <?php }elseif($status == 'shipped'){ ?>
                            出库时间
                            <?php }elseif($status == 'received'){ ?>
                            国内派送时间
                            <?php } else { ?>
                            下单时间
                            <?php } ?>
                        </th>
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
                        <td class="text-center"><?php echo $key+1;?></td>
                        <td class="text-center"><a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'info','id'=>$value['id']));?>"><?php echo $value['sn'];?></a><br><a onclick="showtr('trshow<?php echo $key+1;?>','imgsrc<?php echo $key+1;?>');"><img id="imgsrc<?php echo $key+1;?>" src="tpl/www/images/arrow_down1.png"></a></td>
                        <td class="text-center"><?php if($value['user']){ ?><?php echo $value['user'];?><br><?php echo $value['ucode'];?><?php } else { ?>游客<?php } ?></td>
                        <td class="text-center"><?php echo $value['stock']['title'];?><br><?php echo $channel[$key]['title'];?></td>
                        <td class="text-center"><?php echo $value['batch']['title'];?></td>
                        <td class="text-center"><?php echo $value['jingzhong'];?></td>
                        <td class="text-center"><?php echo $value['price'];?></td>
                        <td class="text-center"><?php echo $value['consignor'];?></td>
                        <td class="text-center"><?php echo $address[$key]['fullname'];?><br><?php echo $address[$key]['mobile'];?></td>
                        <td class="text-center">
                            <?php if($address[$key]['idcard'] && $address[$key]['idcard_front'] && $address[$key]['idcard_back']){ ?><a href="javascript:address_idcard('<?php echo $address[$key]['idcard_front'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-正面"></a><a href="javascript:address_idcard('<?php echo $address[$key]['idcard_back'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-反面"></a><?php }elseif($address[$key]['idcard'] && (!$address[$key]['idcard_front'] || !$address[$key]['idcard_back'])){ ?>有号码，无照片<?php } else { ?><span class="font-red">无身份证</span><?php } ?>
                        </td>
                        <td class="text-center"><?php echo $value['track']['note'] ? $value['track']['note'] : $value['ext'];?></td>
                        <td class="text-center">
                            <?php if($status == 'paid' || $status == 'shipped' || $status == 'received'){ ?>
                            <?php echo date('Y-m-d H:i:s',$value['track']['addtime']);?>
                            <?php } else { ?>
                            <?php echo date('Y-m-d H:i:s',$value['addtime']);?>
                            <?php } ?>
                        </td>
                        <td class="text-left">
                            <?php if($value['status']=='create' || $value['status']=='unpaid' || $value['status']=='pickup'){ ?>
                            <input type="button" value="扣款" onclick="order_pay('<?php echo $value['id'];?>','<?php echo $pageurl;?>')" class="btn btn-xs btn-success" />
                            <?php } ?>
                            <input type="button" value="编辑" onclick="order_edit('<?php echo $value['id'];?>','<?php echo $pageurl;?>')" class="btn btn-xs btn-info" />
                            <?php if($popedom['delete'] && ($value['status']=='create' ||$value['status']=='unpaid')){ ?>
                            <input type="button" value="删除" onclick="remove('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs btn-danger" />
                            <?php } ?>
                            <?php if($value['status']=='create'){ ?>
                            <input type="button" value="复制" onclick="order_copy('<?php echo $value['id'];?>','<?php echo $pageurl;?>')" class="btn btn-xs blue-hoki" />
                            <?php } ?>
                            <?php if($value['status']!='unpaid'){ ?>
                            <input type="button" value="打印" onclick="back_print('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs btn-warning" />
                            <?php } ?>
                            <?php if($value['status']=='paid'){ ?>
                            <input type="button" value="出库" onclick="order_out_single('<?php echo $value['id'];?>')" class="btn btn-xs purple-plum" />
                            <input type="button" value="验重" onclick="order_error('<?php echo $value['sn'];?>')" class="btn btn-xs yellow" />
                            <?php } ?>
                            <?php if($value['status']=='shipped'){ ?>
                            <input type="button" value="状态" onclick="order_delivery('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs green-jungle" />
                            <?php } ?>
                            <?php if($value['status']=='received'){ ?>
                            <input type="button" value="快递" onclick="order_express('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs purple" />
                            <?php } ?>
                        </td>
                    </tr>
                    <tr id="trshow<?php echo $key+1;?>" style="display:none">
                        <td colspan="14" align="left">
                            <div class="modal_border">
                                <b>总费用:</b><?php echo $value['price'];?><?php echo $value['currency']['title'];?> | <b>价值:</b><?php echo $value['val'];?><?php echo $value['currency']['title'];?> | <b>税费:</b><?php echo $value['tax'];?><?php echo $value['currency']['title'];?> | <b>保价:</b><?php echo $value['safe'];?><?php echo $value['currency']['title'];?> | <b>保费:</b><?php echo $value['safe_price'];?><?php echo $value['currency']['title'];?> | <b>增值服务费:</b><?php echo $value['custom_price'];?><?php echo $value['currency']['title'];?>
                            </div>
                            <div class="modal_border">
                                <b>预估重量:</b><?php echo $value['weight'];?><?php echo $value['weight_id'];?><?php if(!$value['jingzhong'] || $value['jingzhong']!='0.00'){ ?> | <b>称重重量:</b><?php echo $value['jingzhong'];?><?php echo $value['weight_id'];?><?php } ?><?php if($value['volume']){ ?> | <b>体积重量:</b><?php echo $value['volume'];?><?php echo $value['weight_id'];?>(<?php echo $value['length'];?>*<?php echo $value['width'];?>*<?php echo $value['height'];?>)<?php } ?><?php if(!$value['pay_weight'] || $value['pay_weight']!='0.00'){ ?> | <b>计费重量:</b><?php echo $value['pay_weight'];?><?php echo $value['weight_id'];?><?php } ?> | <b>增值服务:</b>
                                <?php $list_id["num"] = 0;$value['custom']=is_array($value['custom']) ? $value['custom'] : array();$list_id["total"] = count($value['custom']);$list_id["index"] = -1;foreach($value['custom'] AS $k=>$v){ $list_id["num"]++;$list_id["index"]++; ?>
                                <?php echo $v['title'];?><?php if($list_id['num'] != $list_id['total']){ ?> | <?php } ?>
                                <?php } ?>
                            </div>
                            <div class="modal_border">
                                <b>仓库:</b><?php echo $value['stock']['title'];?><?php if($value['position']<>''){ ?>(<?php echo $value['position'];?>)<?php } ?> <?php if($type){ ?> | <b>业务操作:</b><?php echo $work[$value['type']];?><?php } ?><?php if($value['batch']['title']){ ?> | <b>清关批次:</b><?php } ?><?php echo $value['batch']['title'];?><?php if($value['express_sn']){ ?> | <b>快递单号:</b><?php echo $value['express_sn'];?>(<?php echo $value['express'];?>)<?php } ?> | <b>身份证:</b><?php echo $address[$key]['idcard'];?><?php if($address[$key]['idcard_front']<>''){ ?><a href="javascript:address_idcard('<?php echo $address[$key]['idcard_front'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-正面"></a><?php } ?><?php if($address[$key]['idcard_back']<>''){ ?><a href="javascript:address_idcard('<?php echo $address[$key]['idcard_back'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-反面"></a><?php } ?>
                            </div>
                            <div class="modal_border">
                                <strong>货物:</strong>
                                <?php if($value['pros']){ ?>
                                <?php $list_id["num"] = 0;$value['pros']=is_array($value['pros']) ? $value['pros'] : array();$list_id["total"] = count($value['pros']);$list_id["index"] = -1;foreach($value['pros'] AS $k=>$v){ $list_id["num"]++;$list_id["index"]++; ?>
                                <?php echo $v['brand'];?><?php echo $v['title'];?>*<?php echo $v['qty'];?><?php if($list_id['num'] != $list_id['total']){ ?>|<?php } ?>
                                <?php } ?>
                                <?php } ?>
                                <?php if($value['package']){ ?>
                                | <strong>包裹单号:</strong>
                                <?php $list_id["num"] = 0;$value['package']=is_array($value['package']) ? $value['package'] : array();$list_id["total"] = count($value['package']);$list_id["index"] = -1;foreach($value['package'] AS $k=>$v){ $list_id["num"]++;$list_id["index"]++; ?>
                                <?php echo $v['sn'];?><?php if($list_id['num'] != $list_id['total']){ ?>|<?php } ?>
                                <?php } ?>
                                <?php } ?>
                            </div>
                            <div class="modal_border">
                                <strong>备注:</strong>
                                <?php echo $value['note'];?>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-inline">
                <div class="form-group">
                    <input type="button" value="全选" class="btn btn-xs btn-danger" onclick="$.input.checkbox_all()" />
                    <input type="button" value="全不选" class="btn btn-xs btn-success" onclick="$.input.checkbox_none()" />
                    <input type="button" value="反选" class="btn btn-xs btn-warning" onclick="$.input.checkbox_anti()" />
                </div>
                <div class="form-group">
                    <select id="list_action_val" onchange="update_select()">
                        <option value="">选择要执行的动作</option>
                        <optgroup label="发货流程">
                            <?php $statuslist_id["num"] = 0;$statuslist=is_array($statuslist) ? $statuslist : array();$statuslist_id["total"] = count($statuslist);$statuslist_id["index"] = -1;foreach($statuslist AS $key=>$value){ $statuslist_id["num"]++;$statuslist_id["index"]++; ?>
                            <option value="<?php echo $key;?>"><?php echo $value;?></option>
                            <?php } ?>
                        </optgroup>
                        <?php if($batch_list){ ?>
                        <optgroup label="批次操作">
                            <?php $batch_list_id["num"] = 0;$batch_list=is_array($batch_list) ? $batch_list : array();$batch_list_id["total"] = count($batch_list);$batch_list_id["index"] = -1;foreach($batch_list AS $key=>$value){ $batch_list_id["num"]++;$batch_list_id["index"]++; ?>
                            <option value="cate:<?php echo $value['id'];?>"><?php echo $value['title'];?></option>
                            <?php } ?>
                        </optgroup>
                        <?php } ?>
                    </select>
                </div>
                <?php if($batch_list){ ?>
                <div class="form-group" id="cate_set_li" style="display: none">
                    <select name="cate_set_val" style="margin-top:0px;" id="cate_set_val">
                        <option value="move">绑定批次</option>
                        <option value="cancel">取消批次</option>
                    </select>
                </div>
                <?php } ?>
                <div class="form-group" id="plugin_button"><input type="button" value="执行操作" onclick="list_action_exec()" class="btn btn-xs blue" /></div>
                <a class="btn btn-xs green" href="javascript:void(0);" onclick="order_pays()">
                    <i class="fa fa-cny"></i>
                    批量扣款
                </a>
                <a class="btn btn-xs btn-warning" href="javascript:void(0);" onclick="order_pldy()">
                    <i class="fa fa-print"></i>
                    批量打印
                </a>
                <a href="javascript:;" onclick="order_pdf('A6','a')" class="btn btn-xs btn-info">
                    <i class="fa fa-print"></i> PDF打印 </a>
                <a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="removes()">
                    <i class="fa fa-close"></i>
                    批量删除
                </a>
            </div>
            <div class="col-md-12 text-right"><?php $this->output("pagelist","file"); ?></div>
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
    function tab(s){
		var url;
        url = get_url('order','index','status='+s);
        $.dsy.go(url);
    }
    function order_copy(id,pageurl)
    {
        var url = get_url('order','copy','id='+id+'&pageurl='+pageurl);
        $.dsy.go(url);
    }
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
    function order_pdf(page_format,tp)
    {
        var ids = $.input.checkbox_join();
        if(!ids){
            $.dialog.alert('请选择要批量打印的运单');
            return false;
        }
        var url = get_url('plugin','exec','id=ordertopdf&exec=to_pdflist&ids='+$.str.encode(ids)+'&page_format='+page_format+'&tp='+tp);
        url = $.dsy.nocache(url);
        window.open(url);
        return false;
    }
</script>
<?php $this->output("foot","file"); ?>
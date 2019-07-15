<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","运单列表"); ?><?php $this->output("head_member","file"); ?>
<?php $this->output("nav","file"); ?>
<div class="page-container">
    <?php $this->output("block_usercp","file"); ?>
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet box grey">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-bars"></i>运单列表</div>
                        </div>
                        <div class="portlet-body">
                            <div class="tabbable tabbable-tabdrop">
                                <ul class="nav nav-tabs" style="margin-bottom:0px;">
                                    <li class="<?php if($status==''){ ?>active <?php } ?>bold">
                                        <a href="javascript:void(0)" onclick="tab('')">全部运单<span class="badge badge-success"><?php echo $sum;?></span></a>
                                    </li>
                                    <?php $statuslist_id["num"] = 0;$statuslist=is_array($statuslist) ? $statuslist : array();$statuslist_id["total"] = count($statuslist);$statuslist_id["index"] = -1;foreach($statuslist AS $key=>$value){ $statuslist_id["num"]++;$statuslist_id["index"]++; ?>
                                    <li class="<?php if($status==$key){ ?>active <?php } ?>bold">
                                        <a href="javascript:void(0)" onclick="tab('<?php echo $key;?>')"><?php echo $value;?><span class="badge badge-success"> <?php echo $count[$key];?></span></a>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <div class="tab-content" style="border: 1px solid #ddd;border-top: none;padding:10px;">
                                    <div class="navbar-collapse bg-grey-steel">
                                        <form class="navbar-form navbar-left" method="post" action="<?php echo dsy_url(array('ctrl'=>'order','func'=>'index'));?>" style="padding-left:0px;">
                                            <div class="form-group">
                                                <input class="form-control input-small" type="text" name="sn" value="<?php echo $sn;?>" placeholder="运单号">
                                            </div>
                                            <div class="form-group">
                                                <select name="stock" class="form-control">
                                                    <option value="" selected="selected">请选择仓库</option>
                                                    <?php $stock_id["num"] = 0;$stock=is_array($stock) ? $stock : array();$stock_id["total"] = count($stock);$stock_id["index"] = -1;foreach($stock AS $key=>$value){ $stock_id["num"]++;$stock_id["index"]++; ?>
                                                    <option value="<?php echo $value['id'];?>"<?php if($stock_select == $value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select name="channel" class="form-control">
                                                    <option value="" selected="selected">请选择渠道</option>
                                                    <?php $channel_list_id["num"] = 0;$channel_list=is_array($channel_list) ? $channel_list : array();$channel_list_id["total"] = count($channel_list);$channel_list_id["index"] = -1;foreach($channel_list AS $key=>$value){ $channel_list_id["num"]++;$channel_list_id["index"]++; ?>
                                                    <option value="<?php echo $value['id'];?>"<?php if($channel_search == $value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                 <input id="dateRange" class="form-control" type="text" name="dateRange" value="<?php echo $dateRange;?>" placeholder="下单日期范围"/>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" id="fullname" name="fullname" value="<?php echo $fullname;?>" placeholder="收件人"/>
                                            </div>
                                            <button style="height:32px;" class="btn btn-info" type="submit"><i class="fa fa-search"></i> 查 询 </button>
                                        </form>
                                    </div>
                                    <div class="tab-pane active">
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover">
                                                <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th class="bold text-center">序号</th>
                                                    <th class="bold text-center">运单号</th>
                                                    <th class="bold text-center">仓库/渠道</th>
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
                                                <?php if($rslist){ ?>
                                                <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                            <input class="checkboxes" name="ids[]" value="<?php echo $value['id'];?>" type="checkbox">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td class="text-center"><?php echo $key+1;?></td>
                                                    <td class="text-center"><a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'order_info','id'=>$value['id']));?>"><?php echo $value['sn'];?></a><br><a onclick="showtr('trshow<?php echo $key+1;?>','imgsrc<?php echo $key+1;?>');"><img id="imgsrc<?php echo $key+1;?>" src="tpl/www/images/arrow_down1.png"></a></td>
                                                    <td class="text-center"><?php echo $value['stock']['title'];?><br><span class="font-blue"><?php echo $value['channel']['title'];?></span></td>
                                                    <td class="text-center"><?php echo $value['price'];?></td>
                                                    <td class="text-center"><?php echo $value['consignor'];?></td>
                                                    <td class="text-center"><?php echo $value['address']['fullname'];?><br><span class="font-blue"><?php echo $value['address']['mobile'];?></span></td>
                                                    <td class="text-center">
                                                        <?php if($value['address']['idcard'] && $value['address']['idcard_front'] && $value['address']['idcard_back']){ ?><a href="javascript:address_idcard('<?php echo $value['address']['idcard_front'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-正面"></a><a href="javascript:address_idcard('<?php echo $value['address']['idcard_back'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-反面"></a><?php }elseif($value['address']['idcard'] && (!$value['address']['idcard_front'] || !$value['address']['idcard_back'])){ ?>有号码，无照片<?php } else { ?><span class="font-red">无身份证</span> <?php } ?>
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
                                                        <?php if($value['status']=="create" || $value['status']=="pickup"){ ?>
                                                        <input type="button" value="编辑" onclick="order_edit('<?php echo $value['id'];?>','<?php echo $pageurl;?>')" class="btn btn-xs btn-info" />
                                                        <?php } ?>
														<?php if($value['status']=="create" || $value['status']=="unpaid"){ ?>
                                                        <input type="button" value="删除" onclick="remove('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs btn-danger" />
                                                        <?php } ?>
                                                        <input type="button" value="复制" onclick="order_copy('<?php echo $value['id'];?>')" class="btn btn-xs btn-info" />
                                                        <input type="button" value="跟踪" onclick="order_track('<?php echo $value['sn'];?>')" class="btn btn-xs btn-success" />
                                                        <input type="button" value="打印" onclick="font_print('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs btn-warning" />
                                                        <?php if($value['status']=="received"){ ?>
                                                        <input type="button" value="签收" onclick="order_end('<?php echo $value['id'];?>')" class="btn btn-xs btn-success" />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <tr id="trshow<?php echo $key+1;?>" style="display:none">
                                                    <td colspan="11" align="left">
                                                        <div class="modal_border">
                                                            <b>总费用:</b><?php echo $value['price'];?><?php echo $value['currency']['title'];?> | <b>价值:</b><?php echo $value['val'];?><?php echo $value['currency']['title'];?> | <b>税费:</b><?php echo $value['tax'];?><?php echo $value['currency']['title'];?> | <b>保价:</b><?php echo $value['safe'];?><?php echo $value['currency']['title'];?> | <b>保费:</b><?php echo $value['safe_price'];?><?php echo $value['currency']['title'];?> | <b>增值服务费:</b><?php echo $value['custom_price'];?><?php echo $value['currency']['title'];?>
                                                        </div>
                                                        <div class="modal_border">
                                                            <b>预估重量:</b><?php echo $value['weight'];?><?php echo $value['weight_id'];?><?php if(!$value['jingzhong'] || $value['jingzhong']!='0.00'){ ?> | <b>称重重量:</b><?php echo $value['jingzhong'];?><?php echo $value['weight_id'];?><?php } ?><?php if($value['volume']){ ?> | <b>体积重量:</b><?php echo $value['volume'];?><?php echo $value['weight_id'];?>(<?php echo $value['length'];?>*<?php echo $value['width'];?>*<?php echo $value['height'];?>)<?php } ?><?php if(!$value['pay_weight'] || $value['pay_weight']!='0.00'){ ?> | <b>计费重量:</b><?php echo $value['pay_weight'];?><?php echo $value['weight_id'];?><?php } ?>
                                                        </div>
                                                        <div class="modal_border">
                                                            <b>仓库:</b><?php echo $value['stock']['title'];?><?php if($value['express_sn']){ ?> | <b>快递单号:</b><?php echo $value['express_sn'];?>（<?php echo $value['express'];?>）<?php } ?><?php if($type){ ?> | <b>业务操作:</b><?php echo $work[$value['type']];?><?php } ?> | <b>下单时间:</b><?php echo date('Y-m-d H:i:s',$value['addtime']);?><?php if($value['address']['idcard']<>''){ ?> | <b>身份证:</b><?php echo $value['address']['idcard'];?><?php } ?> <?php if($value['address']['idcard_front']<>''){ ?><a href="javascript:address_idcard('<?php echo $value['address']['idcard_front'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-正面"></a><?php } ?>
                                                            <?php if($value['address']['idcard_back']<>''){ ?><a href="javascript:address_idcard('<?php echo $value['address']['idcard_back'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-反面"></a><?php } ?>
                                                        </div>
                                                        <div class="modal_border">
                                                        <strong>货物：</strong>
                                                        <?php if($value['pros']){ ?>
                                                        <?php $list_id["num"] = 0;$value['pros']=is_array($value['pros']) ? $value['pros'] : array();$list_id["total"] = count($value['pros']);$list_id["index"] = -1;foreach($value['pros'] AS $k=>$v){ $list_id["num"]++;$list_id["index"]++; ?>
                                                        <?php echo $v['brand'];?><?php echo $v['title'];?>X<?php echo $v['qty'];?><?php if($list_id['num'] != $list_id['total']){ ?> | <?php } ?>
                                                        <?php } ?>
                                                        <?php } ?>
                                                        </div>
                                                        <div class="modal_border">
                                                            <strong>备注：</strong>
                                                            <?php echo $value['note'];?>　<a href="javascript:edit_note('<?php echo $value['id'];?>');void(0)" class="label label-sm label-default">修改备注</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                <?php } else { ?>
                                                <tr><td colspan="11"><span class="fa fa-warning">没有记录!</span></td></tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 form-inline">
                                            <div class="form-group">
                                                <input type="button" value="全选" class="btn btn-xs btn-info" onclick="$.input.checkbox_all()" />
                                                <input type="button" value="全不选" class="btn btn-xs btn-success" onclick="$.input.checkbox_none()" />
                                                <input type="button" value="反选" class="btn btn-xs btn-danger" onclick="$.input.checkbox_anti()" />
                                                <input type="button" value="导出运单" class="btn btn-xs btn-info" onclick="exportOrder()" />
                                                <input type="button" value="批量打印" class="btn btn-xs btn-warning" onclick="order_pldy()" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-right"><?php $this->output("block_pagelist","file"); ?></div>
                                    </div>
                                </div>
                            </div>
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
    var url;
    function tab(num){
        url = get_url('order','index','status='+num);
        //window.location.href=url;
        direct(url);
    }
    function order_result(rs)
    {
        if(rs.status == 'ok'){
            $.dialog.alert('<?php echo P_Lang("操作成功");?>',function(){
                $.dsy.reload();
            });
        }else{
            $.dialog.alert(rs.content);
        }
    }
    function order_copy(id)
    {
        var url = get_url('order','copy','id='+id);
        $.dsy.go(url);
    }
    function address_idcard(id)
    {
        var url = api_url('usercp','address_idcard','id='+id);
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
    function edit_note(id)
    {
        var url = get_url('order','edit_note','id='+id);
        $.dialog.open(url,{
            'title':'修改备注',
            'lock':true,
            'width':'50%',
            'height':'50%',
            'ok':function(){
                var iframe = this.iframe.contentWindow;
                if (!iframe.document.body) {
                    alert('iframe还没加载完毕呢');
                    return false;
                };
                iframe.save();
                return false;
            },
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
    function exportOrder()
    {
        var ids = $.input.checkbox_join();
        if(!ids){
            $.dialog.alert('请选择要导出的运单');
            return false;
        }
        var url = get_url('order','exportOrder','ids='+$.str.encode(ids));
        $.dsy.go(url);
        return false;
    }
</script>
<?php $this->output("foot_member","file"); ?>
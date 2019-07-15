<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","待审核运单"); ?><?php $this->output("head_member","file"); ?>
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
                                <i class="fa fa-bars"></i>待审核运单列表</div>
                        </div>
                        <div class="portlet-body">
                            <div class="tabbable tabbable-tabdrop">
                                <div class="tab-content">
                                    <div class="navbar-collapse bg-grey-steel">
                                        <form class="navbar-form navbar-left" method="post" action="<?php echo dsy_url(array('ctrl'=>'order','func'=>'index'));?>" style="padding-left:0px;">
                                            <div class="form-group">
                                                <input class="form-control input-small" type="text" name="sn" value="<?php echo $sn;?>" placeholder="运单号">
                                            </div>
                                            <div class="form-group">
                                                <select name="stock" class="form-control" style="width:135px;">
                                                    <option value="" selected="selected">请选择仓库</option>
                                                    <?php $stock_id["num"] = 0;$stock=is_array($stock) ? $stock : array();$stock_id["total"] = count($stock);$stock_id["index"] = -1;foreach($stock AS $key=>$value){ $stock_id["num"]++;$stock_id["index"]++; ?>
                                                    <option value="<?php echo $value['id'];?>"<?php if($stock_select == $value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <select name="channel" class="form-control" style="width: 150px;">
                                                    <option value="" selected="selected">请选择渠道</option>
                                                    <?php $channel_list_id["num"] = 0;$channel_list=is_array($channel_list) ? $channel_list : array();$channel_list_id["total"] = count($channel_list);$channel_list_id["index"] = -1;foreach($channel_list AS $key=>$value){ $channel_list_id["num"]++;$channel_list_id["index"]++; ?>
                                                    <option value="<?php echo $value['id'];?>"<?php if($channel_search == $value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input id="dateRange" class="form-control" type="text" name="dateRange" value="<?php echo $dateRange;?>" placeholder="日期范围"/>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" id="fullname" name="fullname" value="<?php echo $fullname;?>" placeholder="收件人" style="width: 120px;" />
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
                                                    <th class="bold text-center">运单号</th>
                                                    <th class="bold text-center">渠道</th>
                                                    <th class="bold text-center">仓库</th>
                                                    <th class="bold text-center">收件人</th>
                                                    <th class="bold text-center">状态</th>
                                                    <th class="bold text-center">操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if($rslist){ ?>
                                                <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                                                <tr onclick="TestBlack('trshow<?php echo $key+1;?>');">
                                                    <td class="text-center">
                                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                            <input class="checkboxes" name="ids[]" value="<?php echo $value['id'];?>" type="checkbox">
                                                            <span></span>
                                                        </label>
                                                    </td>
                                                    <td class="text-center"><a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'order_info','id'=>$value['id']));?>"><?php echo $value['sn'];?></a></td>
                                                    <td class="text-center"><?php echo $value['channel']['title'];?></td>
                                                    <td class="text-center"><?php echo $value['stock']['title'];?></td>
                                                    <td class="text-center"><?php echo $value['address']['fullname'];?><br><span class="font-blue-oleo"><?php echo $value['address']['mobile'];?></span></td>
                                                    <td class="text-center"><?php echo $value['track']['note'] ? $value['track']['note'] : $value['ext'];?></td>
                                                    <td class="text-center">
                                                        <input type="button" value="编辑" onclick="order_edit('<?php echo $value['id'];?>','')" class="btn btn-xs btn-info" />
                                                        <input type="button" value="删除" onclick="order_delete('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs btn-danger" />
                                                        <input type="button" value="打印" onclick="font_print('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs btn-warning" />
                                                    </td>
                                                </tr>
                                                <tr id="trshow<?php echo $key+1;?>" style="display:<?php echo $key+1 > 1 ? none: '';?>">
                                                    <td colspan="8" align="left">
                                                        <div class="modal_border">
                                                            总费用：<?php echo $value['price'];?><?php echo $value['currency']['title'];?> | 价值：<?php echo $value['val'];?><?php echo $value['currency']['title'];?> | 税费：<?php echo $value['tax'];?><?php echo $value['currency']['title'];?> | 保价：<?php echo $value['safe'];?><?php echo $value['currency']['title'];?> | 保费：<?php echo $value['safe_price'];?><?php echo $value['currency']['title'];?> | 增值服务费：<?php echo $value['custom_price'];?><?php echo $value['currency']['title'];?>
                                                        </div>
                                                        <div class="modal_border">
                                                            预估重量：<?php echo $value['weight'];?><?php echo $value['weight_id'];?>
															<?php if(!$value['jingzhong'] || $value['jingzhong']!='0.00'){ ?> | 称重重量：<?php echo $value['jingzhong'];?><?php echo $value['weight_id'];?><?php } ?><?php if($value['volume']){ ?> | 体积重量：<?php echo $value['volume'];?><?php echo $value['weight_id'];?>(<?php echo $value['length'];?>*<?php echo $value['width'];?>*<?php echo $value['height'];?>)
															<?php } ?>
															<?php if(!$value['pay_weight'] || $value['pay_weight']!='0.00'){ ?> | 计费重量：<?php echo $value['pay_weight'];?><?php echo $value['weight_id'];?>
															<?php } ?>
															| 预估费用：<?php echo $value['channel_price'];?><?php echo $value['currency']['title'];?>
                                                        </div>
                                                        <div class="modal_border">
                                                            仓库：<?php echo $value['stock']['title'];?>
															<?php if($value['express_sn']){ ?> | 快递单号：<?php echo $value['express_sn'];?>(<?php echo $value['express'];?>)
															<?php } ?>
															<?php if($type){ ?> 
															| 业务操作：<span class="font-blue"><?php echo $work[$value['type']];?></span> 
															<?php } ?> 
															| 下单时间：<?php echo date('Y-m-d H:i:s',$value['addtime']);?>
															<?php if($value['address']['idcard']<>''){ ?>
															| 身份证：<?php echo $value['address']['idcard'];?>
															<?php } ?> 
															<?php if($value['address']['idcard_front']<>''){ ?>
															<a href="javascript:address_idcard('<?php echo $value['address']['idcard_front'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-正面"></a>
															<?php } ?>
                                                            <?php if($value['address']['idcard_back']<>''){ ?>
															<a href="javascript:address_idcard('<?php echo $value['address']['idcard_back'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-反面"></a>
															<?php } ?>
                                                        </div>
                                                        <div class="modal_border">
                                                            <strong>货物：</strong>
                                                            <?php if($value['pros']){ ?>
                                                            <?php $list_id["num"] = 0;$value['pros']=is_array($value['pros']) ? $value['pros'] : array();$list_id["total"] = count($value['pros']);$list_id["index"] = -1;foreach($value['pros'] AS $k=>$v){ $list_id["num"]++;$list_id["index"]++; ?>
                                                            <?php echo $v['brand'];?><?php echo $v['title'];?>X<?php echo $v['qty'];?><?php if($list_id['num'] != $list_id['total']){ ?>　|　<?php } ?>
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
                                                <input type="button" value="全选" class="btn btn-sm btn-danger" onclick="$.input.checkbox_all()" />
                                                <input type="button" value="全不选" class="btn btn-sm btn-success" onclick="$.input.checkbox_none()" />
                                                <input type="button" value="反选" class="btn btn-sm btn-warning" onclick="$.input.checkbox_anti()" />
                                                <input type="button" value="批量打印" class="btn btn-sm btn-info" onclick="order_pldy()" />
                                                <input type="button" value="标签PDF打印" class="btn btn-sm btn-info" onclick="order_pdf()" />
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
    function order_pdf()
    {
        var ids = $.input.checkbox_join();
        if(!ids){
            $.dialog.alert('请选择要批量打印的运单');
            return false;
        }
        var url = get_url('plugin','exec','id=ordertopdf&exec=to_pdflist&ids='+$.str.encode(ids));
        url = $.dsy.nocache(url);
        window.open(url);
        return false;
    }
</script>
<?php $this->output("foot_member","file"); ?>
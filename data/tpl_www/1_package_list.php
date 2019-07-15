<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","我的包裹"); ?><?php $this->output("head_member","file"); ?>
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
                                <i class="fa fa-bars"></i>包裹列表</div>
                        </div>
                        <div class="portlet-body">
                            <div class="tabbable tabbable-tabdrop">
                                <ul class="nav nav-tabs" style="margin-bottom:0px;">
                                    <li class="<?php if($status == ''){ ?>active <?php } ?>bold">
                                        <a href="javascript:void(0)" onclick="tab('')">全部包裹<span class="badge badge-success"><?php echo $sum;?></span></a>
                                    </li>
                                    <?php $statuslist_id["num"] = 0;$statuslist=is_array($statuslist) ? $statuslist : array();$statuslist_id["total"] = count($statuslist);$statuslist_id["index"] = -1;foreach($statuslist AS $key=>$value){ $statuslist_id["num"]++;$statuslist_id["index"]++; ?>
                                    <li class="<?php if($status == $key && $status!=''){ ?>active <?php } ?>bold">
                                        <a href="javascript:void(0)" onclick="tab('<?php echo $key;?>')"><?php echo $value;?><span class="badge badge-success"><?php echo $count[$key];?></span></a>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <div class="tab-content" style="border: 1px solid #ddd;border-top: none;padding:10px;">
                                    <div class="navbar-collapse bg-grey-steel">
                                        <form class="navbar-form navbar-left" method="post" action="<?php echo dsy_url(array('ctrl'=>'package','func'=>'index','status'=>$status));?>">
                                            <div class="row">
                                                <div class="form-group">
                                                    <input class="form-control input-small" type="text" name="sn" value="<?php echo $sn;?>" placeholder="包裹单号" />
                                                </div>
                                                <div class="form-group">
                                                    <input class="form-control input-small" type="text" name="position" value="<?php echo $position;?>" placeholder="仓位" />
                                                </div>
                                                <div class="form-group">
                                                    <select name="stock" class="form-control">
                                                        <option value="">到货仓库</option>
                                                        <?php $stock_list_id["num"] = 0;$stock_list=is_array($stock_list) ? $stock_list : array();$stock_list_id["total"] = count($stock_list);$stock_list_id["index"] = -1;foreach($stock_list AS $key=>$value){ $stock_list_id["num"]++;$stock_list_id["index"]++; ?>
                                                        <option value="<?php echo $value['id'];?>"<?php if($stock == $value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <input id="dateRange" class="form-control" type="text" name="dateRange" value="<?php echo $dateRange;?>" placeholder="日期范围"/>
                                                </div>
                                                <div class="form-group">
                                                    <button style="height:32px;" class="btn btn-info" type="submit"><i class="fa fa-search"></i> 查 询 </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane active">
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="sample_1">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th class="text-center bold">包裹单号</th>
                                                    <th class="text-center bold">仓库/仓位</th>
                                                    <th class="text-center bold">入库重量</th>
                                                    <th class="text-center bold">预报/入库时间</th>
                                                    <th class="text-center bold">状态</th>
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
                                                    <td class="text-center"><a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'info','id'=>$value['id']));?>"><?php echo $value['sn'];?></a></td>
                                                    <td class="text-center"><?php echo $value['stock_list']['title'];?><br><span class="font-blue-oleo"><?php echo $value['position'];?></span></td>
                                                    <td class="text-center"><?php echo $value['jingzhong'];?></td>
                                                    <td class="text-center"><?php if($value['addtime']){ ?><?php echo date('Y-m-d H:i',$value['addtime']);?><?php } else { ?>--<?php } ?><br><span class="font-blue-oleo"><?php if($value['rukutime']){ ?><?php echo date('Y-m-d H:i',$value['rukutime']);?><?php } else { ?>--<?php } ?></span></td>
                                                    <td class="text-center"><?php echo $statuslist[$value['status']];?></td>
                                                    <td class="text-center">
                                                       <?php if($value['status']!='generated'){ ?>
                                                        <input type="button" value="编辑" onclick="package_edit('<?php echo $value['id'];?>')" class="btn btn-xs btn-info" />
                                                        <input type="button" value="删除" onclick="package_delete('<?php echo $value['id'];?>','<?php echo $value['sn'];?>')" class="btn btn-xs btn-danger" />
                                                        <?php } ?>
                                                        <?php if($value['status']=='stored'){ ?>
                                                        <input type="button" value="提交订单" onclick="package_create('<?php echo $value['id'];?>')" class="btn btn-xs btn-warning" />
                                                        <?php } ?>
                                                        <?php if($value['status']=='generated'){ ?>
                                                        <span class="font-blue">
                                                        <?php $value_order_id["num"] = 0;$value['order']=is_array($value['order']) ? $value['order'] : array();$value_order_id["total"] = count($value['order']);$value_order_id["index"] = -1;foreach($value['order'] AS $k=>$v){ $value_order_id["num"]++;$value_order_id["index"]++; ?>
                                                        <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'order_info','id'=>$v['id']));?>" target="_blank"><?php echo $v['sn'];?></a><br>
                                                        <?php } ?>
                                                    </span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
												<tr id="trshow<?php echo $key+1;?>" style="display:<?php echo $key+1 > 1 ? none: '';?>">
                                                    <td colspan="7" align="left">
                                                        <div class="modal_border">
                                                            <strong>物品：</strong>
                                                            <?php if($value['pros']){ ?>
                                                            <?php $value_pros_id["num"] = 0;$value['pros']=is_array($value['pros']) ? $value['pros'] : array();$value_pros_id["total"] = count($value['pros']);$value_pros_id["index"] = -1;foreach($value['pros'] AS $k=>$v){ $value_pros_id["num"]++;$value_pros_id["index"]++; ?>
                                                            <?php echo $v['title'];?>X<?php echo $v['qty'];?>；
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
                                                <?php } else { ?>
                                                <tr><td colspan="7"><span class="fa fa-warning">没有记录!</span></td></tr>
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
                                                <?php if($status =='unstored' || $status == ''){ ?>
                                                <input type="button" value="删除" class="btn btn-sm red" onclick="set_delete()" /><?php } ?>
                                                <?php if($status == 'stored' || $status == ''){ ?>
                                                <input type="button" value="提交订单" class="btn btn-sm btn-info" onclick="package_create(0)" /><?php } ?>
												<input type="button" value="置顶/取消置顶" class="btn btn-sm red" onclick="set_top()" />
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
<script type="text/javascript">
    //日期范围
    laydate.render({
        elem: '#dateRange'
        ,range: true
    });
    var url;
    function tab(num){
        url = get_url('package','index','status='+num);
        //window.location.href=url;
        direct(url);
    }
    function package_create(val){
        if(val){
            url = get_url("order","create") +"&id="+val;
        }else{
            var ids = $.input.checkbox_join();
            if(!ids)
            {
                $.dialog.alert("未指定包裹ID");
                return false;
            }
            url = get_url("order","create") +"&id="+$.str.encode(ids);
        }

        direct(url);
    }
    /*function package_list(id){
        direct("<?php echo dsy_url(array('ctrl'=>'order','func'=>'order_info'));?>&package_id="+id);
    }*/
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
	function set_top()
    {
        var ids = $.input.checkbox_join();
        if(!ids)
        {
            $.dialog.alert("请选择包裹");
            return false;
        }
        $.dialog.confirm("确定要置顶/取消置顶包裹吗？",function(){
            var url = get_url("package","settop") +"&id="+$.str.encode(ids);
            var rs = json_ajax(url);
            if(rs.status == "ok")
            {
                $.dsy.reload();
            }
            else
            {
                $.dialog.alert(rs.content);
                return false;
            }
        });
    }
</script>
<?php $this->output("foot_member","file"); ?>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'package'));?>">包裹管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>包裹列表</span>
        </li>
    </ul>
</div>
<div class="page-bar" style="padding-left:8px;padding-right:8px;">
    <form class="navbar-form navbar-left" method="post" action="<?php echo admin_url('package');?>">
        <div class="row">
            <div class="form-group">
                <select name="status" class="form-control">
                    <option value="">请选择包裹状态</option>
                    <?php $tmpid["num"] = 0;$statuslist=is_array($statuslist) ? $statuslist : array();$tmpid["total"] = count($statuslist);$tmpid["index"] = -1;foreach($statuslist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
                    <option value="<?php echo $key;?>"<?php if($status==$key && $status!=''){ ?> selected<?php } ?>><?php echo $value;?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <select id="stock" name="stock" class="form-control">
                    <option value="">到货仓库…</option>
                    <?php $stocks_id["num"] = 0;$stocks=is_array($stocks) ? $stocks : array();$stocks_id["total"] = count($stocks);$stocks_id["index"] = -1;foreach($stocks AS $key=>$value){ $stocks_id["num"]++;$stocks_id["index"]++; ?>
                    <option value="<?php echo $value['id'];?>"<?php if($stock==$value['id']){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="fullname" value="<?php echo $fullname;?>" placeholder="收件人" />
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="sn" value="<?php echo $sn;?>" placeholder="包裹单号" />
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="ucode" value="<?php echo $ucode;?>" placeholder="会员编号" />
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="user" value="<?php echo $user;?>" placeholder="会员名" />
            </div>
            <div class="form-group">
                <input id="dateRange" class="form-control" type="text" name="dateRange" value="<?php echo $dateRange;?>" placeholder="日期范围"/>
            </div>
            <div class="form-group">
                <button class="btn blue" type="submit"><i class="fa fa-search"></i> 查 询 </button>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs">
                        <li class="<?php if($status == ''){ ?>active <?php } ?>bold">
                            <a href="javascript:void(0)" onclick="tab('')">全部包裹<span class="badge badge-success"><?php echo $sum;?></span></a>
                        </li>
                        <?php $statuslist_id["num"] = 0;$statuslist=is_array($statuslist) ? $statuslist : array();$statuslist_id["total"] = count($statuslist);$statuslist_id["index"] = -1;foreach($statuslist AS $key=>$value){ $statuslist_id["num"]++;$statuslist_id["index"]++; ?>
                        <li class="<?php if($status==$key && $status!=''){ ?>active <?php } ?>bold">
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
                                        <th class="bold text-center">包裹单号</th>
                                        <th class="bold text-center">收件人</th>
                                        <th class="bold text-center">仓库/仓位</th>
                                        <th class="bold text-center">入库重量</th>
                                        <th class="bold text-center">预报/入库时间</th>
                                        <th class="bold text-center">会员</th>
                                        <th class="bold text-center">备注</th>
                                        <th class="bold text-center">状态</th>
                                        <th class="bold text-center">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $tmpid["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$tmpid["total"] = count($rslist);$tmpid["index"] = -1;foreach($rslist AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
                                    <tr>
                                        <td class="text-center">
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input class="checkboxes" id="id_<?php echo $value['id'];?>" name="ids[]" value="<?php echo $value['id'];?>" type="checkbox">
                                                <span></span>
                                            </label>
                                        </td>
                                        <td class="text-center"><a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'info','id'=>$value['id']));?>"><?php echo $value['sn'];?></a><br><a onclick="showtr('trshow<?php echo $key+1;?>','imgsrc<?php echo $key+1;?>');"><img id="imgsrc<?php echo $key+1;?>" src="tpl/www/images/arrow_down1.png"></a></td>
                                        <td class="text-center"><?php echo $value['fullname'];?></td>
                                        <td class="text-center"><?php echo $value['stock_list']['title'];?><br><span class="font-blue-oleo"><?php echo $value['position'];?></span></td>
                                        <td class="text-center"><?php echo $value['jingzhong'];?></span></td>
                                        <td class="text-center"><?php if($value['addtime']){ ?><?php echo date('Y-m-d H:i',$value['addtime']);?><?php } else { ?>--<?php } ?><br><span class="font-blue-oleo"><?php if($value['rukutime']){ ?><?php echo date('Y-m-d H:i',$value['rukutime']);?><?php } else { ?>--<?php } ?></span></td>
                                        <td class="text-center"><?php echo $value['user'];?><br><span class="font-blue-oleo"><?php echo $value['ucode'];?></span></td>
                                        <td><?php echo $value['note'];?></td>
                                        <td class="text-center"><?php echo $statuslist[$value['status']];?></td>
                                        <td class="text-center">
                                            <?php if($popedom['modify'] && $value['status']!='generated'){ ?>
                                            <a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'set','id'=>$value['id']));?>" class="btn btn-xs blue">编辑</a>
                                            <?php } ?>
                                            <?php if($popedom['delete'] && $value['status']!='generated'){ ?>
                                            <input type="button" value="删除" onclick="package_delete(<?php echo $value['id'];?>,'<?php echo $value['sn'];?>')" class="btn btn-xs btn-danger" />
                                            <?php } ?>
                                            <?php if($value['status']=='generated'){ ?>
                                                        <span class="font-blue">
                                                        <?php $value_order_id["num"] = 0;$value['order']=is_array($value['order']) ? $value['order'] : array();$value_order_id["total"] = count($value['order']);$value_order_id["index"] = -1;foreach($value['order'] AS $k=>$v){ $value_order_id["num"]++;$value_order_id["index"]++; ?>
                                                        <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'info','id'=>$v['id']));?>"><?php echo $v['sn'];?></a>
                                                        <?php } ?>
                                                    </span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr id="trshow<?php echo $key+1;?>" style="display:none">
                                        <td colspan="10" align="left">
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
                                <div class="form-group">
                                    <select id="list_action_val"  onchange="update_select()">
                                        <option value="">选择要执行的动作</option>
                                        <?php if($popedom['delete']){ ?>
                                        <option value="delete">批量删除</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group" id="plugin_button"><input type="button" value="执行操作" onclick="list_action_exec()" class="btn btn-xs blue" /></div>
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
<script type="text/javascript">
    //日期范围
    laydate.render({
        elem: '#dateRange'
        ,range: true
    });
    function tab(status){
        var url;
        url = get_url('package','index','status='+status);
        //window.location.href=url;
        direct(url);
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
</script>
<?php $this->output("foot","file"); ?>
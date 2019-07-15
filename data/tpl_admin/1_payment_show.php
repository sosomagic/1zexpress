<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'show'));?>">财务管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>资金明细</span>
        </li>
    </ul>
</div>
<div class="page-bar" style="padding-left:8px;padding-right:8px;">
    <form id="searchForm" class="navbar-form navbar-left" method="post" action="">
        <div class="row">
            <div class="form-group">
                <select name="type" class="form-control">
                    <option value="">交易类型</option>
                    <?php $typelist_id["num"] = 0;$typelist=is_array($typelist) ? $typelist : array();$typelist_id["total"] = count($typelist);$typelist_id["index"] = -1;foreach($typelist AS $key=>$value){ $typelist_id["num"]++;$typelist_id["index"]++; ?>
                    <option value="<?php echo $key;?>"<?php if($type == $key){ ?> selected<?php } ?>><?php echo $value;?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <select name="method" class="form-control">
                    <option value="">支付方式</option>
                    <?php $paymentMethod_id["num"] = 0;$paymentMethod=is_array($paymentMethod) ? $paymentMethod : array();$paymentMethod_id["total"] = count($paymentMethod);$paymentMethod_id["index"] = -1;foreach($paymentMethod AS $key=>$value){ $paymentMethod_id["num"]++;$paymentMethod_id["index"]++; ?>
                    <option value="<?php echo $key;?>"<?php if($method == $key){ ?> selected<?php } ?>><?php echo $value;?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <input class="form-control input-small" type="text" name="order_sn" value="<?php echo $order_sn;?>" placeholder="运单号" />
            </div>
            <div class="form-group">
                <input id="dateRange" class="form-control" type="text" name="dateRange" value="<?php echo $dateRange;?>" placeholder="日期范围"/>
            </div>
            <div class="form-group">
                <input class="form-control input-small" type="text" name="sn" value="<?php echo $sn;?>" placeholder="支付编号" />
            </div>
            <div class="form-group">
                <input class="form-control input-small" type="text" name="user" value="<?php echo $user;?>" placeholder="用户名" />
            </div>
            <div class="form-group">
                <input class="form-control input-xsmall" type="text" name="ucode" value="<?php echo $ucode;?>" placeholder="会员编号" />
            </div>
            <div class="form-group">
                <button class="btn btn-info btn-sm" onclick="gotoSubmit('<?php echo dsy_url(array('ctrl'=>'payment','func'=>'show'));?>')"><i class="fa fa-search"></i> 查 询 </button>
            </div>
            <div class="form-group">
                <button class="btn btn-success btn-sm" onclick="gotoSubmit('<?php echo dsy_url(array('ctrl'=>'payment','func'=>'export_data'));?>')"><i class="fa fa-file-excel-o"></i> 导 出 </button>
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
                        <?php $typelist_id["num"] = 0;$typelist=is_array($typelist) ? $typelist : array();$typelist_id["total"] = count($typelist);$typelist_id["index"] = -1;foreach($typelist AS $key=>$value){ $typelist_id["num"]++;$typelist_id["index"]++; ?>
                        <li class="<?php if($type == $key){ ?>active <?php } ?>bold">
                            <a href="javascript:void(0)" onclick="tab('<?php echo $key;?>')"><?php echo $value;?>记录</a>
                        </li>
                        <?php } ?>
                        <li class="<?php if($type == '' || !$type){ ?>active <?php } ?>bold">
                            <a href="javascript:void(0)" onclick="tab('')">所有记录</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                    <tr>
                                        <th class="bold">支付编号</th>
                                        <th class="bold">类型</th>
                                        <th class="bold">金额</th>
                                        <th class="bold">当前余额($)</th>
                                        <th class="bold">用户名/会员编号</th>
                                        <th class="bold">单号</th>
                                        <th class="bold">渠道</th>
                                        <th class="bold">重量</th>
                                        <th class="bold">备注</th>
                                        <th class="bold">支付方式</th>
                                        <th class="bold">操作人</th>
                                        <th class="bold">创建时间</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                                    <tr>
                                        <td><?php echo $value['sn'];?></td>
                                        <td><?php echo $typelist[$value['type']];?></td>
                                        <td><?php echo price_format($value['price'],$value['currency_id']);?></td>
                                        <td><?php echo $value['balance'];?></td>
                                        <td class="text-center"><?php echo $value['user']['user'] ? $value['user']['user'] : '游客';?><br><?php echo $value['user']['ucode'];?></td>
                                        <td><?php echo $value['order']['sn'];?></td>
                                        <td><?php echo $value['channel']['type'];?></td>
                                        <td><?php echo $value['weight'];?></td>
                                        <td><?php echo $value['content'];?></td>
                                        <td><?php echo $paymentMethod[$value['paymentMethod']];?></td>
                                        <td><?php echo $value['adm']['account'];?></td>
                                        <td><?php echo date('Y-m-d H:i:s',$value['dateline']);?></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php if($pagelist){ ?><div class="pull-right"><?php $this->output("pagelist","file"); ?></div><?php } ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/laydate/laydate.js"></script>
<script type="text/javascript">
    //日期范围
    laydate.render({
        elem: '#dateRange'
        ,range: true
    });
    function tab(type){
        var url = get_url('payment','show','type='+type);
        $.dsy.go(url);
    }
   /*function out_excel()
    {
        var url = get_url('payment','export');
        var title = '导出Excel';
        $.dialog.open(url,{
            'title':title,
            'width':'70%',
            'height':'70%',
            'lock':true
        });
    }*/
    function gotoSubmit(value) {
        var searchForm = $("#searchForm");
        searchForm.attr("action",value);
        searchForm.submit();
    }
</script>
<?php $this->output("foot","file"); ?>
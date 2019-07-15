<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","个人中心"); ?><?php $this->output("head_member","file"); ?>
<script src="js/laydate/laydate.js"></script>
<script src="js/highcharts/code/highcharts.js"></script>
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
                            <i class="fa fa-bars"></i>常用统计</div>
                        <div class="tools">
                            <a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
                            <a title="" data-original-title="" href="javascript:;" class="reload"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'index','status'=>'unstored'));?>" class="icon-btn" style="border-radius: 4px;">
                            <i class="fa fa-cube"></i>
                            <div> 未入库包裹 </div>
                            <span class="badge badge-danger"> <?php echo $weiruku;?> </span>
                        </a>
                        <a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'index','status'=>'stored'));?>" class="icon-btn" style="border-radius: 4px;">
                            <i class="fa fa-cubes"></i>
                            <div> 已入库包裹 </div>
                            <span class="badge badge-info"> <?php echo $yiruku;?> </span>
                        </a>
                        <a href="<?php echo dsy_url(array('ctrl'=>'package','func'=>'index','status'=>'generated'));?>" class="icon-btn" style="border-radius: 4px;">
                            <i class="fa fa-tasks"></i>
                            <div> 生成运单包裹 </div>
                            <span class="badge badge-success"> <?php echo $yishengcheng;?> </span>
                        </a>
                        <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'index','status'=>'create'));?>" class="icon-btn" style="border-radius: 4px;">
                            <i class="fa fa-credit-card"></i>
                            <div> 已下单 </div>
                            <span class="badge badge-warning"> <?php echo $create;?> </span>
                        </a>
                        <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'index','status'=>'paid'));?>" class="icon-btn" style="border-radius: 4px;">
                            <i class="fa fa-bank"></i>
                            <div> 已扣款 </div>
                            <span class="badge badge-danger"> <?php echo $paid;?> </span>
                        </a>
                        <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'index','status'=>'shipped'));?>" class="icon-btn" style="border-radius: 4px;">
                            <i class="fa fa-sign-in"></i>
                            <div> 已出库 </div>
                            <span class="badge badge-success"> <?php echo $shipped;?> </span>
                        </a>
                        <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'index','status'=>'unpaid'));?>" class="icon-btn" style="border-radius: 4px;">
                            <i class="fa fa-random"></i>
                            <div> 余额不足 </div>
                            <span class="badge badge-danger"> <?php echo $unpaid;?> </span>
                        </a>
                        <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'index','status'=>'received'));?>" class="icon-btn" style="border-radius: 4px;">
                            <i class="fa fa-truck"></i>
                            <div> 国内派送 </div>
                            <span class="badge badge-success"> <?php echo $received;?> </span>
                        </a>
                        <a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'delivery'));?>" class="icon-btn" style="border-radius: 4px;">
                            <i class="fa fa-suitcase"></i>
                            <div> 取件管理 </div>
                            <span class="badge badge-success"> <?php echo $delivery;?> </span>
                        </a>
						<a href="<?php echo dsy_url(array('ctrl'=>'order','func'=>'apply'));?>" class="icon-btn" style="border-radius: 4px;">
                            <i class="fa fa-cubes"></i>
                            <div> 直接下单 </div>
                            <span class="badge badge-success"> <?php echo $delivery;?> </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-md-6 col-sm-6">
				<div class="portlet box grey">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-bars"></i>个人信息</div>
						<div class="tools">
							<a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover">
							<tbody>
							<tr>
								<td>会员名：<?php echo $rs['user'];?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;会员等级：<?php echo $group['title'];?></td>
							</tr>
							<tr>
								<td>会员编号：<span class="font-red"><?php echo $rs['ucode'];?></span>&nbsp;&nbsp;&nbsp;&nbsp;会员ID:<?php echo $rs['id'];?>
								</td>
							</tr>
							<tr>
								<td><?php $tmpid["num"] = 0;$user['wealth']=is_array($user['wealth']) ? $user['wealth'] : array();$tmpid["total"] = count($user['wealth']);$tmpid["index"] = -1;foreach($user['wealth'] AS $key=>$value){ $tmpid["num"]++;$tmpid["index"]++; ?>
									<span><?php echo $value['title'];?>：</span>
									<span style="margin-right: 20px;"><?php echo $value['val'];?><?php echo $value['unit'];?>&nbsp;&nbsp;<?php if($value['unit'] =='美元'){ ?><a href="<?php echo dsy_url(array('ctrl'=>'payment','func'=>'recharge'));?>">[充值]</a><?php } ?></span>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									当前汇率：美元/人民币 = 1/<?php echo $currency['val'];?>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-6">
				<div class="portlet box grey">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-bars"></i>新闻公告</div>
						<div class="tools">
							<a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
						</div>
					</div>
					<div class="portlet-body">
						<div class="scroller" style="height: 170px;">
							<ul class="feeds">
								<?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
								<li>
									<a href="index.php?id=<?php echo $value['id'];?>" target="_blank">
										<div class="col1">
											<div class="cont">
												<div class="cont-col1">
													<div class="label label-sm label-default">
														<i class="icon-bulb"></i>
													</div>
												</div>
												<div class="cont-col2">
													<div class="desc"><?php echo $value['title'];?></div>
												</div>
											</div>
										</div>
										<div class="col2">
											<div class="date">
												<?php echo date('Y.m.d',$value['dateline']);?>
											</div>
										</div>
									</a>
								</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box grey">
                    <div class="portlet-title">
                        <div class="caption">
                                <i class="fa fa-bars"></i>仓库地址<span class="font-red">（请在购物网站填写收货人时，务必将First Name与Last Name信息填写完整。）</span></div>
                        <div class="tools">
                            <a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <?php $stock_list_id["num"] = 0;$stock_list=is_array($stock_list) ? $stock_list : array();$stock_list_id["total"] = count($stock_list);$stock_list_id["index"] = -1;foreach($stock_list AS $key=>$value){ $stock_list_id["num"]++;$stock_list_id["index"]++; ?>
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <thead>
                            <tr>
                                <th class="bold"><?php echo $value['title'];?></th>
                                <th colspan="3"><?php echo $value['note'];?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-right col-md-2">收件人(Name)：</td>
                                    <td class="col-md-3">First Name：<?php if($rs['first_name']){ ?><span class="font-red"><?php echo $rs['first_name'];?></span><?php } else { ?><a href="<?php echo dsy_url(array('ctrl'=>'usercp','func'=>'info'));?>" class="btn btn-xs default">完善资料</a><?php } ?><br>Last Name：<span class="font-red"><?php echo $rs['last_name'];?></span></td>
                                <td class="text-right col-md-2">地址(Address)：</td>
                                <td class="col-md-5"><?php echo $value['address'];?>,<?php echo $value['city'];?> <?php echo $value['province'];?></td>
                            </tr>
                            <tr>
                                <td class="text-right col-md-2">邮编(Zip)：</td>
                                <td class="col-md-3"><?php echo $value['zipcode'];?></td>
                                <td class="text-right col-md-2">电话(Tel)：</td>
                                <td class="col-md-5"><?php echo $value['tel'];?></td>
                            </tr>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box grey">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-bars"></i>当前价格</div>
                        <div class="tools">
                            <a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <thead>
                            <tr>
                                <th class="bold">线路名称</th>
                                <th class="bold">抹零设置</th>
                                <th class="bold">计重模式</th>
                                <th class="bold">起重</th>
                                <th class="bold">首磅费用</th>
                                <th class="bold">续磅费用</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $channel_list_id["num"] = 0;$channel_list=is_array($channel_list) ? $channel_list : array();$channel_list_id["total"] = count($channel_list);$channel_list_id["index"] = -1;foreach($channel_list AS $key=>$value){ $channel_list_id["num"]++;$channel_list_id["index"]++; ?>
                            <tr>
                                <td><?php echo $value['title'];?></td>
                                <td><?php echo $value['num'];?></td>
                                <td><?php if($value['rule']=='ceil'){ ?>进位取整<?php }elseif($value['rule']=='round'){ ?>0.5进制<?php } else { ?>实际计重<?php } ?></td>
                                <td><?php echo $value['start_heavy'];?></td>
                                <td><?php echo $value['first_price'];?></td>
                                <td><?php echo $value['second_price'];?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<!-- 公告弹窗 -->
<?php if($notice['title']){ ?>
<a class="btn btn-xs btn-default" data-toggle="modal" href="#at" id='popup' style="display:none"></a>
<div class="modal fade" id="at" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-wide">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><?php echo $notice['title'];?></h4>
            </div>
            <div class="modal-body">
                <?php echo $notice['content'];?>
                <div class="text-right font-grey">
                    <?php echo date('Y年m月d日',$notice['dateline']);?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"> 关 闭 </button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#popup').click();
    });
</script>
<?php } ?>
<?php $this->output("foot_member","file"); ?>
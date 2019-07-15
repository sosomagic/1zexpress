<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'channel','func'=>'price'));?>">运费设置</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>运费设置列表</span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                        <tr>
                            <th class="bold text-center">会员组</th>
                            <th class="bold text-center">资费标准</th>
                            <th class="bold text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                        <tr>
                            <td class="text-center" style="vertical-align: middle !important;"><?php echo $value['title'];?></td>
                            <td>
                                <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                    <tr>
                                        <th class="bold text-center">线路名称</th>
                                        <th class="bold text-center">抹零设置</th>
                                        <th class="bold text-center">计重模式</th>
                                        <th class="bold text-center">起步重量</th>
                                        <th class="bold text-center">首磅费用</th>
                                        <th class="bold text-center">续磅费用</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $value_price_id["num"] = 0;$value['price']=is_array($value['price']) ? $value['price'] : array();$value_price_id["total"] = count($value['price']);$value_price_id["index"] = -1;foreach($value['price'] AS $k=>$v){ $value_price_id["num"]++;$value_price_id["index"]++; ?>
                                    <tr>
                                        <td class="text-center"><?php echo $v['channel']['title'];?></td>
                                        <td class="text-center"><?php if($v['num']<>'0.00'){ ?><?php echo $v['num'];?><?php } else { ?>无<?php } ?></td>
                                        <td class="text-center"><?php if($v['rule']=='ceil'){ ?>进位取整<?php }elseif($v['rule']=='round'){ ?>0.5进制<?php } else { ?>实际计重<?php } ?></td>
                                        <td class="text-center"><?php echo $v['start_heavy'];?></td>
                                        <td class="text-center"><?php echo $v['first_price'];?></td>
                                        <td class="text-center"><?php echo $v['second_price'];?></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </td>
                            <td class="text-center" style="vertical-align: middle !important;">
                                <?php if($popedom['modify']){ ?>
                                <a class="btn btn-xs btn-info" href="<?php echo dsy_url(array('ctrl'=>'channel','func'=>'setprice','id'=>$value['id']));?>"><i class="fa fa-edit"></i> 编辑</a>
                                <?php } ?>
                                <?php if($popedom['delete']){ ?>
                                <a class="btn btn-xs btn-danger" href="javascript:;" onclick="price_del('<?php echo $value['id'];?>','<?php echo $value['title'];?>')"><i class="fa fa-times"></i> 删除</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo add_js('channel.js');?>"></script>
<?php $this->output("foot","file"); ?>
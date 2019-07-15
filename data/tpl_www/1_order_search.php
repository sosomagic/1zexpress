<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                        <tr>
                            <th class="bold col-md-3 text-center">时间</th>
                            <th class="bold col-md-9 text-center">物流信息</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($rslist){ ?>
                        <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                        <tr>
                            <td class="text-center"><?php echo date('Y-m-d H:i:s',$value['addtime']);?></td>
                            <td><?php echo $value['note'];?></td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr><td colspan="2" class="text-center"><span class="fa fa-warning">没有记录!</span></td></tr>
                        <?php } ?>
						<?php if($rs){ ?>
                        <tr><td colspan="2"><span class="fa fa-lightbulb-o font-red">　如无物流信息，请到<a href="<?php echo $rs['homepage'];?>" target="_blank"> <?php echo $rs['company'];?></a>官方网站查询！</span></td></tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->output("foot_open","file"); ?>
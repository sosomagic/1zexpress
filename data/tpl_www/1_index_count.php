<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <tbody>
                        <tr>
                            <th class="bold text-center">渠道:</th>
                            <td class="text-center"><?php echo $rs['title'];?></td>
                        </tr>
                        <tr>
                            <th class="bold text-center">重量:</th>
                            <td class="text-center"><?php echo $weight;?> <?php echo $rs['weight_id'];?></td>
                        </tr>
                        <!--<tr>
                            <th class="bold text-center">体积:</th>
                            <td class="text-center"><?php echo $volume;?> {rs.weight_id}</td>
                        </tr>-->
                        <tr>
                            <th class="bold text-center">费用:</th>
                            <td class="text-center"><?php echo $channel_price;?> <?php echo $currency['title'];?></td>
                        </tr>
                        <tr><td colspan="2">※ 不含保险费、自定义服务费。</td></tr>
                        <tr><td colspan="2">※ 除上估价费用之外、货物在您当地海关通关作业时按照各国的规定，有时需要另外支付关税（针对非包税渠道）。</td></tr>
                        <tr><td colspan="2">※ 以上价格按普通会员估算，每个等级对应不同运费折扣，以实际支付为准。</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->output("foot_open","file"); ?>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <form method="post" id="setsubmit" onsubmit="return false;">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right col-md-2">运单号：</td>
                            <td><?php echo $rs['sn'];?></td>
                        </tr>
                        <tr>
                            <td class="text-right">预估重量：</td>
                            <td><?php echo $rs['weight'];?><?php echo $rs['weight_unit'][$rs['weight_id']];?></td>
                        </tr>
                        <tr>
                            <td class="text-right">计费重量：</td>
                            <td><?php echo $rs['pay_weight'];?><?php echo $rs['weight_unit'][$rs['weight_id']];?></td>
                        </tr>
                        <tr>
                            <td class="text-right">复　　重：</td>
                            <td><input type="text"  name="jingzhong" value="<?php echo $rs['jingzhong'];?>"  class="input-small" onkeyup="value=value.replace(/[^\d.]/g,'')" /> <?php echo $rs['weight_id'];?></td>
                        </tr>
                        <tr>
                            <td class="text-right">税　　费：</td>
                            <td><input type="text"  name="tax" value="<?php echo $rs['tax'];?>"  class="input-small" onkeyup="value=value.replace(/[^\d.]/g,'')" /> 美元</td>
                        </tr>
                        <tr>
                            <td class="text-right">支付方式：</td>
                            <td colspan="3">
                                <div class="md-radio-inline">
                                    <?php $pay_id["num"] = 0;$pay=is_array($pay) ? $pay : array();$pay_id["total"] = count($pay);$pay_id["index"] = -1;foreach($pay AS $key=>$value){ $pay_id["num"]++;$pay_id["index"]++; ?>
                                    <div class="md-radio">
                                        <input type="radio" name="pay" id="pay_<?php echo $key;?>" value="<?php echo $key;?>" class="md-radiobtn"<?php if($key == 'balance'){ ?> checked<?php } ?>>
                                        <label for="pay_<?php echo $key;?>">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> <?php echo $value;?>
                                        </label>
                                    </div>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function save()
    {
        var obj = art.dialog.opener;
        var id = '<?php echo $rs['id'];?>';
        var url = get_url('order','pay_error');
        if(id){
            url += "&id="+id;
        }
        $("#setsubmit").ajaxSubmit({
            'url':url,
            'type':'post',
            'dataType':'json',
            'success':function(rs){
                if(rs.status == 'ok'){
                    var tip = '运单重新扣款成功';
                    $.dialog.alert(tip,function(){
                        //top.$.dsy.reload();
                        obj.$.dialog.close();
                        obj.$.dsy.reload();
                    },'succeed');
                }else{
                    $.dialog.alert(rs.content);
                    return false;
                }
            }
        });
        return false;
    }
</script>
<?php $this->output("foot_open","file"); ?>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <form method="post" id="setsubmit" onsubmit="return false;">
                    <table class="table table-striped table-bordered table-advance table-hover">
                        <thead>
                        <tr>
                            <th class="bold text-center">运单号</th>
                            <th class="bold text-center">称重重量</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $list_id["num"] = 0;$list=is_array($list) ? $list : array();$list_id["total"] = count($list);$list_id["index"] = -1;foreach($list AS $key=>$value){ $list_id["num"]++;$list_id["index"]++; ?>
                        <tr>
                            <td class="text-center"><?php echo $value['sn'];?><input type="hidden" name="id[]" value="<?php echo $value['id'];?>" /></td>
                            <td class="text-center"><input type="text" name="jingzhong[]" class="form-control" placeholder="称重重量" onkeyup="value=value.replace(/[^\d.]/g,'')" value="<?php echo $value['jingzhong'];?>" /></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="text-center bold">支付方式：</td>
                            <td class="text-center" colspan="2">
                                <select name="pay" class="form-control">
                                    <?php $pay_id["num"] = 0;$pay=is_array($pay) ? $pay : array();$pay_id["total"] = count($pay);$pay_id["index"] = -1;foreach($pay AS $key=>$value){ $pay_id["num"]++;$pay_id["index"]++; ?>
                                    <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                    <?php } ?>
                                </select>
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
    var loading_action;
    function save()
    {
        var obj = art.dialog.opener;
        var url = get_url('order','pays_data');
        $("#setsubmit").ajaxSubmit({
            'url':url,
            'type':'post',
            'dataType':'json',
			'beforeSubmit':function(){
                loading_action = $.dialog.tips('<img src="images/loading.gif" border="0" align="absmiddle" /> 正在保存数据，请稍候…').time(30).lock();
            },
            'success':function(rs){
			    if(loading_action){
					loading_action.close();
				}
                if(rs.status == 'ok'){
                    var tip = '操作成功';
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
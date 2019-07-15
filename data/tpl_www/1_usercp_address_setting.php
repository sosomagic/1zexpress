<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <form method="post" id="setsubmit" onsubmit="return false;">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right">收件人(Recipients):<span class="font-red"> * </span></td>
                            <td><input type="text" id="fullname" name="fullname" value="<?php echo $rs['fullname'];?>" style="width: 100%" />
                                <span class="help-block">请填写收件人真实姓名</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">手机(Mobile):<span class="font-red"> * </span></td>
                            <td><input type="text" id="mobile" name="mobile" value="<?php echo $rs['mobile'];?>" style="width: 100%" /></td>
                        </tr>
                        <tr>
                            <td class="text-right">国家(Country)：<span class="font-red"> * </span></td>
                            <td>
                                <select class="form-control" name="country" id="country">
                                    <?php $country_id["num"] = 0;$country=is_array($country) ? $country : array();$country_id["total"] = count($country);$country_id["index"] = -1;foreach($country AS $key=>$value){ $country_id["num"]++;$country_id["index"]++; ?>
                                    <option value="<?php echo $value;?>"<?php if($rs['country']==$value){ ?> selected="selected"<?php } ?>><?php echo $value;?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr id="prov">
                            <td class="text-right">所在省市(Province)：<span class="font-red"> * </span></td>
                            <td><?php echo $pca_rs;?></td>
                        </tr>
                        <tr id="p">
                            <td class="text-right">州/省(State)：<span class="font-red"> * </span></td>
                            <td><input type="text" name="prov"  value="<?php echo $rs['province'];?>" style="width: 100%" /></td>
                        </tr>
                        <tr id="c">
                            <td class="text-right">城市(City)：<span class="font-red"> * </span></td>
                            <td><input type="text" name="city" value="<?php echo $rs['city'];?>" style="width: 100%" /></td>
                        </tr>
                        <tr>
                            <td class="text-right">详细地址(Address):<span class="font-red"> * </span></td>
                            <td><input type="text" name="address" id="address" value="<?php echo $rs['address'];?>" style="width: 100%" /></td>
                        </tr>
                        <tr>
                            <td class="text-right">邮编(Postcode)：</td>
                            <td><input type="text" id="zipcode" name="zipcode" value="<?php echo $rs['zipcode'];?>" data-code="<?php echo $rs['zipcode'];?>" style="width: 100%"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">备用电话(Second mobile):</td>
                            <td><input type="text" id="tel" name="tel" value="<?php echo $rs['tel'];?>" style="width: 100%" />
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">邮箱(Email):</td>
                            <td><input type="text" id="email" name="email" value="<?php echo $rs['email'];?>" style="width: 100%" /></td>
                        </tr>
                        <tr id="sfz">
                            <td class="text-right">身份证号码(ID Card)：</td>
                            <td><input type="text" id="idcard" name="idcard" value="<?php echo $rs['idcard'];?>" style="width: 100%"/>
                            </td>
                        </tr>
                        <?php $extlist_id["num"] = 0;$extlist=is_array($extlist) ? $extlist : array();$extlist_id["total"] = count($extlist);$extlist_id["index"] = -1;foreach($extlist AS $key=>$value){ $extlist_id["num"]++;$extlist_id["index"]++; ?>
                        <tr id="sfz_up_<?php echo $key;?>">
                            <td class="text-right"><?php echo $value['title'];?>：</td>
                            <td><?php echo $value['html'];?></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var cuys="0";
    function read_county_pca(){
        if($("#zipcode").attr("data-code")!="" && cuys=="0"){
            cuys="1";
            return;
        }
        var zip_id = $("#pca_c").find("option:selected").attr('zip');
        $("#zipcode").val(zip_id);
    }
    function save()
    {
        var id = '<?php echo $id;?>';
        var url = api_url('usercp','address_setting');
        if(id){
            url += "&id="+id;
        }
        $("#setsubmit").ajaxSubmit({
            'url':url,
            'type':'post',
            'dataType':'json',
            'success':function(rs){
                if(rs.status == 'ok'){
                    var tip = id ? '地址信息修改成功' : '地址信息添加成功';
                    $.dialog.alert(tip,function(){
                        top.$.dsy.reload();
                        //$.dsy.go('<?php echo dsy_url(array('ctrl'=>'open','func'=>'address'));?>&id=0');
                    },'succeed');
                }else{
                    $.dialog.alert(rs.content);
                    return false;
                }
            }
        });
        return false;
    }
    $(document).ready(function(){
        var val;
        val = $('#country').val();
            if(val!='中国'){
                $('#prov').hide();
                $('#p').show();
                $('#c').show();
                $('#sfz').hide();
                $('#sfz_up_0').hide();
                $('#sfz_up_1').hide();
            }else{
                $('#prov').show();
                $('#p').hide();
                $('#c').hide();
                $('#sfz').show();
                $('#sfz_up_0').show();
                $('#sfz_up_1').show();
        }

        $("#country").change(function(){
            val = $("#country").val();
            if(val!='中国'){
                $('#prov').hide();
                $('#p').show();
                $('#c').show();
                $('#sfz').hide();
                $('#sfz_up_0').hide();
                $('#sfz_up_1').hide();
            }else{
                $('#prov').show();
                $('#p').hide();
                $('#c').hide();
                $('#sfz_up_0').show();
                $('#sfz_up_1').show();
            }
        });
    });
</script>
<?php $this->output("foot_open","file"); ?>
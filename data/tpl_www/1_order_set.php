<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","编辑运单"); ?><?php $this->output("head_member","file"); ?>
<link rel="stylesheet" href="tpl/www/css/jquery.bigautocomplete.css" type="text/css" />
<script type="text/javascript" src="tpl/www/js/jquery.bigautocomplete.js"></script>
<?php $this->output("nav","file"); ?>
<div class="page-container">
<?php $this->output("block_usercp","file"); ?>
<div class="page-content-wrapper">
<div class="page-content">
<form method="post" id="saveorder">
<?php if($id){ ?>
<input type="hidden" name="id" value="<?php echo $id;?>" />
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bars"></i>编辑运单</div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td class="text-right col-md-2">发货仓库：<span class="font-red"> * </span></td>
                        <td>
                            <select class="form-control" id="stock" name="stock">
                                <option value="">请选择发货仓库</option>
                                <?php $stock_id["num"] = 0;$stock=is_array($stock) ? $stock : array();$stock_id["total"] = count($stock);$stock_id["index"] = -1;foreach($stock AS $key=>$value){ $stock_id["num"]++;$stock_id["index"]++; ?>
                                <option value="<?php echo $value['id'];?>"<?php if($rs['stock_id']==$value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">渠道：<span class="font-red"> * </span></td>
                        <td>
                            <select class="form-control" name="channel" id="channel">
                                <option value="">请选择发货渠道</option>
                                <?php $channel_list_id["num"] = 0;$channel_list=is_array($channel_list) ? $channel_list : array();$channel_list_id["total"] = count($channel_list);$channel_list_id["index"] = -1;foreach($channel_list AS $key=>$value){ $channel_list_id["num"]++;$channel_list_id["index"]++; ?>
                                <option value="<?php echo $value['id'];?>|<?php echo $value['num'];?>|<?php echo $value['rule'];?>|<?php echo $value['start_heavy'];?>|<?php echo $value['first_price'];?>|<?php echo $value['second_price'];?>|<?php echo $value['currency_id'];?>|<?php echo $value['weight_id'];?>|<?php echo $value['percent'];?>|<?php echo $value['tax'];?>|<?php echo $value['from_sn'];?>|<?php echo $value['tracking_number'];?>|<?php echo $value['idcard'];?>|<?php echo $value['vol'];?>"<?php if($rs['channel']==$value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bars"></i>发件人信息<em class="small">(英文填写,为空就默认仓库信息作为发货人信息)</em></div>
                <div class="text-right" style="margin-top:6px;">
                    <a id="sender_1" href="javascript:;" class="btn btn-circle green-haze btn-outline sbold uppercase btn-sm">选择发件人</a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td class="text-right">发件人姓名：</td>
                        <td><input id="consignor_0" type="text" name="consignor" value="<?php echo $rs['consignor'];?>" class="form-control input-sm" />
                        </td>
                        <td class="text-right">发件人电话：</td>
                        <td><input id="consignor_tel_0" type="text" name="consignor_tel" value="<?php echo $rs['consignor_tel'];?>" class="form-control input-sm" />
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">发件人地址：</td>
                        <td colspan="3"><input id="consignor_address_0" type="text" name="consignor_address" value="<?php echo $rs['consignor_address'];?>" class="form-control input-sm" />
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bars"></i>收件人信息</div>
            </div>
            <div class="portlet-body table-scrollable">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td class="text-right">收件人姓名：<span class="font-red"> * </span></td>
                        <td>
                            <input class="form-control input-sm" type="text" id="fullname" name="fullname" value="<?php echo $address['fullname'];?>" autocomplete="off">
                        </td>
                        <td class="text-right">收件人手机：<span class="font-red"> * </span></td>
                        <td><input class="form-control input-sm" type="text" name="mobile" id="mobile" value="<?php echo $address['mobile'];?>" autocomplete="off"></td>
                    </tr>
                    <tr>
                        <td class="text-right">所在省市：<span class="font-red"> * </span></td>
                        <td id="prov"><?php echo $pca_rs;?></td>
                        <td class="text-right">详细地址：<span class="font-red"> * </span></td>
                        <td><input class="form-control input-sm" type="text" name="address" id="address" value="<?php echo $address['address'];?>" autocomplete="off"></td>
                    </tr>
                    <tr>
                        <td class="text-right">邮编：</td>
                        <td><input class="form-control input-sm" type="text" name="zipcode" id="zipcode" value="<?php echo $address['zipcode'];?>" autocomplete="off"></td>
                        <td class="text-right">收件人身份证号：</td>
                        <td><input class="form-control input-sm" type="text" name="idcard" id="idcard" value="<?php echo $address['idcard'];?>" autocomplete="off"></td>
                    </tr>
                    <tr>
                        <?php $extlist_id["num"] = 0;$extlist=is_array($extlist) ? $extlist : array();$extlist_id["total"] = count($extlist);$extlist_id["index"] = -1;foreach($extlist AS $key=>$value){ $extlist_id["num"]++;$extlist_id["index"]++; ?>
                        <td class="text-right"><?php echo $value['title'];?>：</td>
                        <td class="picshenfz"><?php echo $value['html'];?></td>
                        <?php } ?>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bars"></i>物品信息</div>
            </div>
            <div class="portlet-body">
                <input id="pro_cate" value="<?php $prolist_id["num"] = 0;$prolist=is_array($prolist) ? $prolist : array();$prolist_id["total"] = count($prolist);$prolist_id["index"] = -1;foreach($prolist AS $key=>$value){ $prolist_id["num"]++;$prolist_id["index"]++; ?>&lt;option value=&quot;<?php echo $value['title'];?>&quot;&gt;&nbsp;<?php echo $value['title'];?>&nbsp;&lt;/option&gt;<?php } ?>" type="hidden">
                <input id="currency" name="currency" type="hidden" value="美元">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover" id="tab">
                        <thead>
                        <tr>
                            <?php if($prolist){ ?><th class="bold text-center">类别</th><?php } ?>
                            <th class="bold text-center">品牌<span class="font-red">*</span></th>
                            <th class="bold text-center">物品名称<span class="font-red">*</span></th>
                            <th class="bold text-center">规格</th>
                            <th class="bold text-center">数量<span class="font-red">*</span></th>
                            <th class="bold text-center">单价(<span class="dj"></span>)<span class="font-red">*</span></th>
                            <th class="bold text-center">合计(<span class="dj"></span>)<span class="font-red">*</span></th>
                            <th class="text-center bold">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!$id){ ?>
                        <tr>
                            <?php if($prolist){ ?><td><select class="input-small" name='catename[]' id="catename"></select></td><?php } ?>
                            <td><input class="input-small" name="brand[]" type="text" placeholder="英文品牌" required=""></td>
                            <td><input class="input-small" name="title[]" type="text" placeholder="物品名称" required=""></td>
                            <td><input class="input-small" name="size[]" type="text" placeholder="多少克/粒/型号"></td>
                            <td><input class="count input-xsmall" name="qty[]" type="text" placeholder="数量" required="" onkeyup="value=value.replace(/[^\d]/g,'')"></td>
                            <td><input class="unit_price input-xsmall" name="price[]" type="text" placeholder="单价" required="" onkeyup="value=value.replace(/[^\d.]/g,'')"></td>
                            <td><input class="price input-xsmall" name="total_price[]" type="text" placeholder="合计" readonly='readonly'></td>
                            <td class="text-center"><a id="addItem" href="javascript:;"><img src="tpl/www/images/add.gif" alt="增加" title="增加"></a></td>
                        </tr>
                        <?php } else { ?>
                        <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                        <tr id="<?php echo $key+1;?>">
                            <input type="hidden" name="pro_id[]" value="<?php echo $value['id'];?>" />
                            <?php if($prolist){ ?><td><input class="input-small" name="catename[]" type="text" value="<?php echo $value['catename'];?>" required=""></td><?php } ?>
                            <td><input class="input-small" name="brand[]" type="text" value="<?php echo $value['brand'];?>"  required=""></td>
                            <td><input id="pro_title_<?php echo $key+1;?>" class="input-small" name="title[]" type="text" value="<?php echo $value['title'];?>" required=""></td>
                            <td><input class="input-small" name="size[]" type="text" value="<?php echo $value['size'];?>"></td>
                            <td><input class="count input-xsmall" name="qty[]" type="text" value="<?php echo $value['qty'];?>" required="" onkeyup="value=value.replace(/[^\d]/g,'')"></td>
                            <td><input class="unit_price input-xsmall" name="price[]" type="text" value="<?php echo $value['price'];?>" required="" onkeyup="value=value.replace(/[^\d.]/g,'')"></td>
                            <td><input class="price input-xsmall" name="total_price[]" type="text" value="<?php echo $value['total_price'];?>" readonly='readonly'></td>
                            <td class="text-center"><?php if($key==0){ ?><a id="addItem" href="javascript:;"><img src="tpl/www/images/add.gif" alt="增加" title="增加申报商品"></a><?php } else { ?><a href='javascript:;' onclick='package_pro_delete("<?php echo $value['id'];?>","<?php echo $key+1;?>")'><img src='tpl/www/images/del.gif' title="删除申报商品" ></a><?php } ?></td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <script>
                    $(document).ready(function(){
                        var cate_option = $("#pro_cate").val();
                        $("#catename").append(cate_option);
                        //<tr/>居中
                        $("#tab tr").attr("align","center");
                        //增加<tr/>
                        $("#addItem").click(function(){
                            var _len = $("#tab tr").length;
                            var html="";
                            html +="<tr id="+_len+" align='center'>";
                            html +="<input type='hidden' name='pro_id[]' value='add'/>";
                            if(cate_option){
                                html +="<td><select class='input-small' name='catename[]'>"+cate_option+"</select></td>";
                            }
                            html +="<td><input class='input-small' name='brand[]' type='text' placeholder='英文品牌' required=''></td>";
                            html +="<td><input class='input-small' name='title[]' type='text' placeholder='物品名称' required=''></td>";
                            html +="<td><input class='input-small' name='size[]' type='text' placeholder='多少克/粒/型号' ></td>";
                            html +="<td><input class='count input-xsmall' name='qty[]' type='text' placeholder='数量' required='' onkeyup=value=value.replace(/[^\\d]/g,'')></td>";
                            html +="<td><input class='unit_price input-xsmall' name='price[]' type='text' placeholder='单价' required='' onkeyup=value=value.replace(/[^\\d.]/g,'')></td>";
                            html +="<td><input class='price input-xsmall' name='total_price[]' type='text' placeholder='合计' readonly='readonly'></td>";
                            html +="<td><a href='javascript:;' onclick='delItem("+_len+")'><img src='tpl/www/images/del.gif' title='删除申报商品'></a></td></tr>";
                            $("#tab").append(html);
                        })
                    })
                    //删除<tr/>
                    function delItem(num) {
                        $("tr[id='"+num+"']").remove();//删除当前行

                        return false;
                    }
                    //删除运单产品，删除操作成功会更新运单金额
                    function package_pro_delete(id,numid)
                    {
                        var title = $("#pro_title_"+numid).val();
                        $.dialog.confirm("确定要删除物品：<span class='font-red'>"+title+"</span>",function(){
                            var url = get_url('order','product_delete','id='+id);
                            var rs = json_ajax(url);
                            if(rs.status == 'ok')
                            {
                                $.dialog.alert("物品：<span class='font-red'>"+title+"</span> 已成功删除！",function(){
                                    //$.dsy.reload();
                                    $("tr[id='"+numid+"']").remove();//删除当前行
                                });
                            }
                            else
                            {
                                if(!rs.content) rs.content = '删除失败';
                                $.dialog.alert(rs.content);
                                return false;
                            }
                        });
                    }
                </script>
                <div class="clearfix"></div>
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td class="text-right">申报价值：</td>
                        <td><input id="val" name="val" class="input-small" value="<?php echo $rs['val'];?>" onkeyup="value=value.replace(/[^\d.]/g,'')" data-insure-tax=""/> <span class="dj"></span>
                        </td>
                        <td class="text-right">税     费：</td>
                        <td colspan="5"><input id="tax" name="tax" class="input-small" value="<?php echo $rs['tax'];?>" onkeyup="value=value.replace(/[^\d.]/g,'')" /> <span class="dj"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">物品保价：</td>
                        <td><input id="safe" type="text" name="safe" value="<?php echo $rs['safe'];?>"  class="input-small" onkeyup="value=value.replace(/[^\d.]/g,'')"data-insure-percent="" /> <span class="dj"></span>
                        </td>
                        <td class="text-right">保价费用：</td>
                        <td><input id="safe_price" name="safe_price" class="input-small" value="<?php echo $rs['safe_price'];?>" readonly="" /> <span class="dj"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">预估重量：</td>
                        <td><input id="wt" name="wt" class="input-small" value="<?php echo $rs['weight'];?>" onkeyup="value=value.replace(/[^\d.]/g,'')"/> <span class="zl"></span>
                        </td>
                        <td class="text-right">备注：</td>
                        <td>
                            <textarea id="note" name="note" maxlength="100" class="form-control input-sm" rows="2"><?php echo $rs['note'];?></textarea>
                        </td>
                    </tr>
					<?php if($custom){ ?>
                    <tr>
                        <td class="text-right">增值服务：</td>
                        <td colspan="3">
                            <div class="md-checkbox-inline">
                                <?php $custom_id["num"] = 0;$custom=is_array($custom) ? $custom : array();$custom_id["total"] = count($custom);$custom_id["index"] = -1;foreach($custom AS $key=>$value){ $custom_id["num"]++;$custom_id["index"]++; ?>
                                <div class="md-checkbox">
                                    <input id="custom_<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" name="custom[]" type="checkbox" <?php if(in_array($value['id'],$custom_array)){ ?> checked<?php } ?>>
                                    <label for="custom_<?php echo $value['id'];?>">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                        <?php echo $value['title'];?><?php if($value['price'] <>'0.00'){ ?><i class="font-grey-gallery "> <?php echo $value['price'];?> <span class="dj"></span> </i> <?php } ?><?php if($value['note']){ ?>（<?php echo $value['note'];?>）<?php } ?>
                                    </label>
                                </div>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
					<?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-center">
        <button class="btn blue" type="submit">
            <i class="fa fa-edit"></i>
            编 辑
        </button>
    </div>
</div>
</form>
</div>
</div>
</div>
<script type="text/javascript">
    var is_id = '<?php echo $id;?>';
	var pageurl = '<?php echo $pageurl;?>';
    $(document).ready(function(){
        $("#saveorder").submit(function(){
            //通过Ajax提交运单，客户端检查运单信息是否完整
            $(this).ajaxSubmit({
                'url':api_url('order','set'),
                'type':'post',
                'dataType':'json',
                'success':function(rs){
                    //运单不成功的说明
                    if(rs.status != 'ok'){
                        if(!rs.content) rs.content = '运单编辑失败';
                        alert(rs.content);
                        return false;
                    }
                    else
                    {
                        $.dialog.alert("运单编辑成功",function(){
                            //$.dsy.go('<?php echo dsy_url(array('ctrl'=>'order'));?>');
							$.dsy.go(pageurl);
                        },'succeed');
                    }
                }
            });
            return false;
        });
        $("#sender_1").click(function(){
            var url = get_url("open","sender") + "&id=0";
            $.dialog.open(url,{
                title: "收件人管理",
                lock : true,
                width: "700px",
                height: "300px",
                resize: false
            });
        });

        var cost,arr,url,rs;
        if(is_id && is_id != '0'){
            cost = $("#channel").val();
            arr = cost.split("|");
            $(".zl").text(arr[7]);
            $('#safe').attr('data-insure-percent',arr[8]);
            $('#val').attr('data-insure-tax',arr[9]);
            url = get_url('order','currency','id='+arr[6]);
            rs = json_ajax(url);
            if(rs.status == 'ok')
            {
                $(".dj").text(rs.content.currency);
            }

            $("#channel").change(function(){
                cost = $("#channel").val();
                if(!cost){
                    $.dialog.alert("请选择发货渠道");
                }else{
                    arr = cost.split("|");
                    $(".zl").text(arr[6]);
                    $('#safe').attr('data-insure-percent',arr[8]);
                    $('#val').attr('data-insure-tax',arr[9]);
                    url = get_url('order','currency','id='+arr[6]);
                    rs = json_ajax(url);
                    if(rs.status == 'ok')
                    {
                        $(".dj").text(rs.content.currency);
                    }
                }
            });
        }
    });
    //------------------------------------------------申报价格
    $('[name="val"]').bind('keyup', function(){
        var tax = $(this).attr('data-insure-tax');
        if (tax)
        {
            $('[name="tax"]').val(parseFloat($(this).val(), 10)*tax*0.01);

        }
        var all_price = 0;
        $('#tab tr').each(function(){
            var count = parseInt($(this).find('.count').val(), 10),unit_price = parseFloat($(this).find('.unit_price').val(), 10);
            if (count && unit_price) all_price += count*unit_price;
        });

        if (all_price < parseFloat($(this).val(), 10))
        {
            alert('申报价值不能超过商品总价');
            $(this).val('');
        }

    });
    $('[name="safe"]').bind('keyup', function(){
        var percent = $(this).attr('data-insure-percent');
        if (percent)
        {
            $('[name="safe_price"]').val(parseFloat($(this).val(), 10)*percent*0.01);
        }
        if (parseFloat($('[name="val"]').val(), 10) < parseFloat($(this).val(), 10))
         {
         alert('物品保价不能高于申报价值');
         $(this).val('');
         }
         else
         {

         }
    });
    //------------------------------------------------
    function setProcuctPrice(self)
    {
        var obj = self.parents('tr');
        var count = parseInt(obj.find('.count').val(), 10),unit_price = parseFloat(obj.find('.unit_price').val(), 10);
        if (count && unit_price)
        {
            obj.find('.price').val(count*unit_price);
            var max = 0;
            $('#tab tbody tr').each(function(){
                var count = parseInt($(this).find('.count').val(), 10),unit_price = parseFloat($(this).find('.unit_price').val(), 10);
                //console.log($(this).find('.count').val());
                max = max + count*unit_price;
            });
            $('[name="val"]').val(max);
        }
    }

    $('#tab').delegate('.count', 'keyup', function(){
        setProcuctPrice($(this));
        //setProcuctWeight($(this));
    });
    $('#tab').delegate('.unit_price', 'keyup', function(){
        setProcuctPrice($(this));
        // setProcuctDesc($(this));
    });
    function address_idcard(id)
    {
        var url = api_url('usercp','address_idcard','id='+id);
        var title = '身份证照片';

        $.dialog.open(url,{
            'title':title,
            'lock':true,
            'width':'318px',
            'height':'438px',
            'cancel':function(){
                return true;
            }
        });
    }
    var cuys="0";
    function read_county_pca(){
        if($("#zipcode").attr("data-code")!=undefined){
            if($("#zipcode").attr("data-code")!="" && cuys=="0"){
                cuys="1";
                return;
            }
        }
        var zip_id = $("#pca_c").find("option:selected").attr('zip');
        $("#zipcode").val(zip_id);
    }
    var t_user_id = "<?php echo $session['user_id'];?>";
    $("#fullname").bigAutocomplete({
        width:390,
        d_r:'fullname',
        url:'index.php?c=user&f=address&user_id='+t_user_id,
        callback:function(data){
            var url = get_url('user','address_one','id='+data.id);
            var rs = json_ajax(url);
            if(rs.status == 'ok'){$("#prov").html(rs.content);}
            $("#fullname").val(data.fullname);
            $("#idcard").val(data.idcard);
            $("#mobile").val(data.mobile);
            $("#address").val(data.address);
            $("#zipcode").val(data.zipcode);
            $("._e_upload").show();
            $("#idcard_front").val("");
            $("#idcard_back").val("");
            $(".ttye").remove();
            if(data.idcard_front!="" && data.idcard_back!=""){
                $("._e_upload").hide();
                $("#idcard_front").val(data.idcard_front.replace(/(^\s*)|(\s*$)/g, ""));
                $("#idcard_back").val(data.idcard_back.replace(/(^\s*)|(\s*$)/g, ""));
                $(".picshenfz").eq(0).append('<a class="ttye" href="javascript:void(0)" onclick="address_idcard('+data.idcard_front.replace(/(^\s*)|(\s*$)/g, "")+');"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-正面"></a>');
                $(".picshenfz").eq(1).append('<a  class="ttye" href="javascript:void(0)" onclick="address_idcard('+data.idcard_back.replace(/(^\s*)|(\s*$)/g, "")+');" ><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-反面"></a>');
                $(".ttye").show();
            }
        }
    });
</script>
<?php $this->output("foot_member","file"); ?>
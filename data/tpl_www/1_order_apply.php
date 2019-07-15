<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","现场创建运单"); ?><?php $this->output("head_member","file"); ?>
<link rel="stylesheet" href="tpl/www/css/jquery.bigautocomplete.css" type="text/css" />
<script type="text/javascript" src="tpl/www/js/jquery.bigautocomplete.js"></script>
<?php $this->output("nav","file"); ?>
<div class="page-container">
<?php $this->output("block_usercp","file"); ?>
<div class="page-content-wrapper">
<div class="page-content">
<form method="post" id="saveorder">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box grey">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-bars"></i>现场创建运单<em class="font-sm">(<span class="font-red">*</span>号必填项)</em></div>
                    <div class="tools font-red">当前板块为实体店直接发货客户下单专用</div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right"><span class="font-red"> * </span>发货仓库：</td>
                            <td>
                                <select class="form-control" id="stock" name="stock">
                                    <option value="">请选择发货仓库</option>
                                    <?php $stock_id["num"] = 0;$stock=is_array($stock) ? $stock : array();$stock_id["total"] = count($stock);$stock_id["index"] = -1;foreach($stock AS $key=>$value){ $stock_id["num"]++;$stock_id["index"]++; ?>
                                    <option value="<?php echo $value['id'];?>"<?php if($user['stock_id'] == $value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right col-md-2"><span class="font-red"> * </span>渠道：</td>
                            <td>
                                <select class="form-control" name="channel" id="channel">
                                    <option value="">请选择发货渠道</option>
                                    <?php $channel_list_id["num"] = 0;$channel_list=is_array($channel_list) ? $channel_list : array();$channel_list_id["total"] = count($channel_list);$channel_list_id["index"] = -1;foreach($channel_list AS $key=>$value){ $channel_list_id["num"]++;$channel_list_id["index"]++; ?>
                                    <option value="<?php echo $value['id'];?>|<?php echo $value['num'];?>|<?php echo $value['rule'];?>|<?php echo $value['start_heavy'];?>|<?php echo $value['first_price'];?>|<?php echo $value['second_price'];?>|<?php echo $value['currency_id'];?>|<?php echo $value['weight_id'];?>|<?php echo $value['percent'];?>|<?php echo $value['tax'];?>|<?php echo $value['from_sn'];?>|<?php echo $value['tracking_number'];?>|<?php echo $value['idcard'];?>|<?php echo $value['vol'];?>"<?php if($user['channel_id'] == $value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                    <?php } ?>
                                </select>
                                <span class="help-block font-red" id="notice"></span>
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
                        <i class="fa fa-bars"></i>发件人信息<em class="small">(为空默认仓库信息作为发货人信息)</em></div>
                    <div class="text-right" style="margin-top:6px;">
                        <a id="sender_1" href="javascript:;" class="btn btn-circle green-haze btn-outline sbold uppercase btn-sm">选择发件人</a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right">发件人姓名：</td>
                            <td><input id="consignor_0" type="text" name="consignor" value="<?php echo $sender['fullname'];?>" class="form-control" />
                            </td>
                            <td class="text-right">发件人电话：</td>
                            <td><input id="consignor_tel_0" type="text" name="consignor_tel" value="<?php echo $sender['tel'];?>" class="form-control" />
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">发件人地址：</td>
                            <td colspan="3"><input id="consignor_address_0" type="text" name="consignor_address" value="<?php echo $sender['address'];?>" class="form-control" />
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
                        <i class="fa fa-bars"></i>收件人信息<em class="small">(当收件人信息已存在，会自动搜索收件人信息，鼠标点击即可完成自动填写)</em></div>
                </div>
                <div class="portlet-body table-scrollable">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right"><span class="font-red"> * </span>收件人姓名：</td>
                            <td>
                                <input class="form-control input-sm" type="text" id="fullname" name="fullname" value="<?php echo $rs['fullname'];?>" autocomplete="off">
                            </td>
                            <td class="text-right"><span class="font-red"> * </span>收件人手机：</td>
                            <td><input class="form-control input-sm" type="text" name="mobile" id="mobile" value="<?php echo $rs['mobile'];?>" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td class="text-right"><span class="font-red"> * </span>所在省市：</td>
                            <td id="prov"><?php echo $pca_rs;?></td>
                            <td class="text-right"><span class="font-red"> * </span>详细地址：</td>
                            <td><input class="form-control input-sm" type="text" name="address" id="address" value="<?php echo $rs['address'];?>" placeholder="无需包含省市信息" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td class="text-right">邮编：</td>
                            <td><input class="form-control input-sm" type="text" name="zipcode" id="zipcode" value="<?php echo $rs['zipcode'];?>" autocomplete="off"></td>
                            <td class="text-right">收件人身份证号：</td>
                            <td><input class="form-control input-sm" type="text" name="idcard" id="idcard" value="<?php echo $rs['idcard'];?>" autocomplete="off"></td>
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
                        <i class="fa fa-bars"></i>物品信息<em class="small">(请至少录入一条物品信息，方可创建运单)</em></div>
                </div>
                <div class="portlet-body">
                    <input id="pro_cate" value="<?php $prolist_id["num"] = 0;$prolist=is_array($prolist) ? $prolist : array();$prolist_id["total"] = count($prolist);$prolist_id["index"] = -1;foreach($prolist AS $key=>$value){ $prolist_id["num"]++;$prolist_id["index"]++; ?>&lt;option value=&quot;<?php echo $value['title'];?>&quot;&gt;&nbsp;<?php echo $value['title'];?>&nbsp;&lt;/option&gt;<?php } ?>" type="hidden">
                    <input id="currency" name="currency" type="hidden" value="美元">
                    <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-advance table-hover" id="tab">
                        <thead>
                        <tr>
                            <?php if($prolist){ ?><th class="bold">类别<span class="font-red">*</span></th><?php } ?>
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
                        <tr>
                            <?php if($prolist){ ?><td><select class="input-xsmall" name='catename[]' id="catename"></select></td><?php } ?>
                            <td><input class="input-small" name="brand[]" type="text" placeholder="英文品牌" required=""></td>
                            <td><input class="input-small" name="title[]" type="text" placeholder="物品" required=""></td>
                            <td><input class="input-small" name="size[]" type="text" placeholder="多少克/粒/型号"></td>
                            <td><input style="width: 60px;" class="count" name="qty[]" type="text" placeholder="数量" required="" onkeyup="value=value.replace(/[^\d]/g,'')"></td>
                            <td><input style="width: 60px;" class="unit_price" name="price[]" type="text" placeholder="单价" required="" onkeyup="value=value.replace(/[^\d.]/g,'')"></td>
                            <td><input style="width: 60px;" class="price" name="total_price[]" type="text" placeholder="合计" readonly='readonly'></td>
                            <td class="text-center"><a id="addItem" href="javascript:;"><img src="tpl/www/images/add.gif" alt="增加" title="增加申报商品"></a></td>
                        </tr>
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
                                if(cate_option){
                                    html +="<td><select class='input-xsmall' name='catename[]'>"+cate_option+"</select></td>";
                                }
                                html +="<td><input class='input-small' name='brand[]' type='text' placeholder='英文品牌' required=''></td>";
                                html +="<td><input class='input-small' name='title[]' type='text' placeholder='物品' required=''></td>";
                                html +="<td><input class='input-small' name='size[]' type='text' placeholder='多少克/粒/型号'></td>";
                                html +="<td><input style='width: 60px;' class='count' name='qty[]' type='text' placeholder='数量' required='' onkeyup=value=value.replace(/[^\\d]/g,'')></td>";
                                html +="<td><input style='width: 60px;' class='unit_price' name='price[]' type='text' placeholder='单价' required='' onkeyup=value=value.replace(/[^\\d.]/g,'')></td>";
                                html +="<td><input style='width: 60px;' class='price' name='total_price[]' type='text' placeholder='合计' onkeyup=value=value.replace(/[^\\d.]/g,'')></td>";
                                html +="<td><a href='javascript:;' onclick='delItem("+_len+")'><img src='tpl/www/images/del.gif' title='删除申报商品'></a></td></tr>";
                                $("#tab").append(html);
                            })
                        })
                        //删除<tr/>
                        function delItem(num) {
                            $("tr[id='"+num+"']").remove();//删除当前行

                            return false;
                        }
                    </script>
                    <div class="clearfix"></div>
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>

                        <tr>
                            <td class="text-right">申报价值：</td>
                            <td><input id="val" name="val" data-insure-percent=""  onkeyup="value=value.replace(/[^\d.]/g,'')"/> <span class="dj"></span>
                            </td>
                            <td class="text-right">税     费：</td>
                            <td><input id="tax" name="tax" value="" onkeyup="value=value.replace(/[^\d.]/g,'')"/> <span class="dj"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">物品保价：</td>
                            <td><input id="safe" type="text" name="safe" value=""  data-insure-percent="" /> <span class="dj"></span>
                            </td>
                            <td class="text-right">保价费用：</td>
                            <td><input id="safe_price" name="safe_price" value="" readonly="" /> <span class="dj"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right" style="width:102px;">预估重量：</td>
                            <td><input id="wt" name="wt" value="" onkeyup="value=value.replace(/[^\d.]/g,'')"/> <span class="zl"></span>
                            </td>
                            <td class="text-right">备注：</td>
                            <td>
                                <textarea id="note" name="note" maxlength="100" class="form-control input-sm" rows="2"></textarea>
                            </td>
                        </tr>
						<?php if($custom){ ?>
                        <tr>
                            <td class="text-right">增值服务：</td>
                            <td colspan="3">
                                <div class="md-checkbox-inline">
                                    <?php $custom_id["num"] = 0;$custom=is_array($custom) ? $custom : array();$custom_id["total"] = count($custom);$custom_id["index"] = -1;foreach($custom AS $key=>$value){ $custom_id["num"]++;$custom_id["index"]++; ?>
                                    <div class="md-checkbox">
                                        <input id="custom_<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" name="custom[]" type="checkbox">
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
                    <div class="note note-info" style="margin-top: 20px;">
                        <h4 class="block fa fa-warning bold font-red"> 注意：</h4>
                        <p>温馨提示： 渠道商对清关文件要求很严，必须写明货品完整英文品牌、品名、数量等信息。如因虚假申报，照成的不良后果，我司不予负责。 </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <button class="btn blue" type="submit">
                <i class="fa fa-edit"></i>
                提 交
            </button>
        </div>
    </div>
</form>
</div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
		var loading_action;
        $("#saveorder").submit(function(){
            //通过Ajax提交订单，客户端检查订单信息是否完整
            $(this).ajaxSubmit({
                'url':api_url('order','apply'),
                'type':'post',
                'dataType':'json',
				'beforeSubmit':function(){
                    loading_action = $.dialog.tips('<img src="tpl/www/images/loading.gif" border="0" align="absmiddle" /> 正在保存数据，请稍候…').time(30).lock();
                },
                'success':function(rs){
                    if(loading_action){
                        loading_action.close();
                    }
                    if(rs.status != 'ok'){
                        if(!rs.content) rs.content = '运单创建失败';
                        $.dialog.alert(rs.content);
                        return false;
                    }
                    else
                    {
                        $.dialog.alert("运单创建成功",function(){
                            $.dsy.go('<?php echo dsy_url(array('ctrl'=>'order'));?>');
                        },'succeed');
                    }
                }
            });
            return false;
        });
        $("#sender_1").click(function(){
            var url = get_url("open","sender") + "&id=0";
            $.dialog.open(url,{
                title: "发件人管理",
                lock : true,
                width: "70%",
                height: "100%",
                resize: false
            });
        });
        var cost,arr,url,rs;
        $("#channel").change(function(){
            cost = $("#channel").val();
            if(!cost){
                $.dialog.alert("请选择发货渠道");
            }else{
                arr = cost.split("|");
                $(".zl").text(arr[7]);
                $('#safe').attr('data-insure-percent',arr[8]);
                $('#val').attr('data-insure-percent',arr[9]);
                if(arr[13]!=0){$('#notice').text('该渠道针对大件物品可能涉及体积费，由仓库操作员根据最后打包尺寸确定。');
                }else{
                    $('#notice').text('');
                }
                //先填商品，再选渠道
                if($('#val').val()){
                    $('[name="tax"]').val(parseFloat($('#val').val(), 10)*$('#val').attr('data-insure-percent')*0.01);
                }
                if($('#safe').val()){
                    $('[name="safe_price"]').val(parseFloat($('#safe').val(), 10)*$('#safe').attr('data-insure-percent')*0.01);
                }
                url = get_url('order','currency','id='+arr[6]);
                rs = json_ajax(url);
                if(rs.status == 'ok')
                {
                    $("#currency").val(rs.content.currency);
                    $(".dj").text(rs.content.currency);
                }
            }
        });
        //用户设置了默认线路
        cost = $("#channel").val();
        arr = cost.split("|");
        $('#val').attr('data-insure-percent',arr[9]);
        url = get_url('order','currency','id='+arr[6]);
        rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $("#currency").val(rs.content.currency);
            $(".dj").text(rs.content.currency);
        }
    });
    //------------------------------------------------申报价格
    $('[name="val"]').bind('keyup', function(){
        var all_price = 0;
        $('#tab tr').each(function(){

            var count = parseInt($(this).find('.count').val(), 10),unit_price = parseFloat($(this).find('.unit_price').val(), 10);

            if (count && unit_price) all_price += count*unit_price;

        });

        if (all_price < parseFloat($(this).val(), 10))
        {
            alert('申报价格不能超过商品总价');
            $(this).val('');
        }
        var tax = $(this).attr('data-insure-percent');
        if (tax)
        {
            $('[name="tax"]').val(parseFloat($(this).val(), 10)*tax*0.01);
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

        var count = parseInt(obj.find('.count').val(), 10),
                unit_price = parseFloat(obj.find('.unit_price').val(), 10);

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
            //计算预缴税费
            var tax = $('[name="val"]').attr('data-insure-percent');
            if (tax)
            {
                $('[name="tax"]').val(parseFloat(max, 10)*tax*0.01);
            }
        }
    }
    $('#tab').delegate('.count', 'keyup', function(){
        setProcuctPrice($(this));
    });

    $('#tab').delegate('.unit_price', 'keyup', function(){
        setProcuctPrice($(this));
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
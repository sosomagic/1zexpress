<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<style type="text/css">
    #auto_div {
        position: relative;
        z-index: 999;
        left: 0px;
        top: -1px;
        width: 100%;
        border: 1px solid #91B6D2;
        display: none;
        background: #FFF;
        width:100%;
    }
</style>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('order');?>" title="返回运单列表">运单管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span><?php if($id){ ?>运单扣款<?php } ?></span>
        </li>
    </ul>
</div>
<form method="post" id="ordersave">
<?php if($id){ ?>
<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
<input type="hidden" name="s-id" value="<?php echo $shipping['id'];?>" />
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <?php if($id){ ?>
                    <tr>
                        <td class="text-right">运单号：</td>
                        <td colspan="3"><?php echo $rs['sn'];?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td class="text-right"><span class="font-red">*</span>会员编号：</td>
                        <td colspan="3">
                            <input type="hidden" id="user_id" name="user_id" value="<?php echo $rs['user_id'];?>">
                            <input type="text" id="ucode" name="ucode" value="<?php echo $rs['ucode'];?>" class="form-control"/>
                            <span id="show" class="help-block"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">仓库：</td>
                        <td colspan="3">
                            <select class="form-control" id="stock" name="stock">
                                <option value="">请选择发货仓库</option>
                                <?php $stock_id["num"] = 0;$stock=is_array($stock) ? $stock : array();$stock_id["total"] = count($stock);$stock_id["index"] = -1;foreach($stock AS $key=>$value){ $stock_id["num"]++;$stock_id["index"]++; ?>
                                <option value="<?php echo $value['id'];?>"<?php if($rs['stock_id']==$value['id']){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">发货渠道：</td>
                        <td colspan="3">
                            <select class="form-control" id="channel" name="channel">
                                <option value="">请选择发货渠道</option>
                                <?php $channel_id["num"] = 0;$channel=is_array($channel) ? $channel : array();$channel_id["total"] = count($channel);$channel_id["index"] = -1;foreach($channel AS $key=>$value){ $channel_id["num"]++;$channel_id["index"]++; ?>
                                <option value="<?php echo $value['id'];?>|<?php echo $value['num'];?>|<?php echo $value['rule'];?>|<?php echo $value['start_heavy'];?>|<?php echo $value['first_price'];?>|<?php echo $value['second_price'];?>|<?php echo $value['currency_id'];?>|<?php echo $value['weight_id'];?>|<?php echo $value['percent'];?>|<?php echo $value['tax'];?>|<?php echo $value['from_sn'];?>|<?php echo $value['tracking_number'];?>|<?php echo $value['idcard'];?>|<?php echo $value['vol'];?>"<?php if($rs['channel']==$value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td class="text-right"><span class="font-red"> * </span>收件人姓名：</td>
                        <td>
                            <input class="form-control input-sm" type="text" id="fullname" name="fullname" value="<?php echo $shipping['fullname'];?>" autocomplete="off">
                            <div id="auto_div"></div>
                        </td>
                        <td class="text-right"><span class="font-red"> * </span>收件人手机：</td>
                        <td><input class="form-control input-sm" type="text" name="mobile" id="mobile" value="<?php echo $shipping['mobile'];?>"></td>

                    </tr>
                    <tr>

                        <td class="text-right"><span class="font-red"> * </span>所在省市：</td>
                        <td id="prov"><?php echo $pca_rs;?></td>
                        <td class="text-right"><span class="font-red"> * </span>详细地址：</td>
                        <td><input class="form-control input-sm" type="text" name="address" id="address" value="<?php echo $shipping['address'];?>" placeholder="无需包含省市信息" ></td>
                    </tr>
                    <tr>
                        <td class="text-right">邮编：</td>
                        <td><input class="form-control input-sm" type="text" name="zipcode" id="zipcode" value="<?php echo $shipping['zipcode'];?>"></td>
                        <td class="text-right">收件人身份证号：</td>
                        <td><input class="form-control input-sm" type="text" name="idcard" id="idcard" value="<?php echo $shipping['idcard'];?>"></td>

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
            <div class="portlet-body">
                <input id="pro_cate" value="<?php $catelist_id["num"] = 0;$catelist=is_array($catelist) ? $catelist : array();$catelist_id["total"] = count($catelist);$catelist_id["index"] = -1;foreach($catelist AS $key=>$value){ $catelist_id["num"]++;$catelist_id["index"]++; ?>&lt;option value=&quot;<?php echo $value['title'];?>&quot;&gt;<?php echo $value['title'];?>&lt;/option&gt;<?php } ?>" type="hidden">
                <table class="table table-striped table-bordered table-advance table-hover" id="prolist">
                    <thead>
                    <tr>
                        <?php if($catelist){ ?><th class="bold">类别</th><?php } ?>
                        <th class="bold text-center">品牌<span class="font-red"> * </span></th>
                        <th class="bold text-center">物品名称<span class="font-red"> * </span></th>
                        <th class="bold text-center">规格</th>
                        <th class="bold text-center">数量<span class="font-red"> * </span></th>
                        <th class="bold text-center">单价(<span class="dj"></span>)<span class="font-red"> * </span></th>
                        <th class="bold text-center">合计(<span class="dj"></span>)<span class="font-red"> * </span></th>
                        <th class="text-center"><a onclick="add_row()" href="javascript:;"><img src="tpl/www/images/add.gif" alt="增加" title="增加申报商品"></a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                    <tr id="pro_<?php echo $rslist_id['num'];?>" class="prolist">
                        <input type="hidden" name="pro_id[]" value="<?php echo $value['id'];?>" />
                        <?php if($catelist){ ?><td><input type="text" name="catename[]" value="<?php echo $value['catename'];?>" class="input-small"/></td><?php } ?>
                        <td><input type="text" name="brand[]" value="<?php echo $value['brand'];?>" required="" class="input-small"/></td>
                        <td><input id="pro_title_<?php echo $key+1;?>" type="text" name="title[]" value="<?php echo $value['title'];?>" required="" class="input-small"/></td>
                        <td><input type="text" name="size[]" value="<?php echo $value['size'];?>" class="input-small"/></td>
                        <td><input type="text" name="qty[]" class="count input-xsmall" value="<?php echo $value['qty'];?>" onkeyup="value=value.replace(/[^\d.]/g,'')" /></td>
                        <td class="center"><input type="text" name="price[]" class="unit_price input-xsmall" value="<?php echo $value['price'];?>" onkeyup="value=value.replace(/[^\d.]/g,'')" /></td>
                        <td class="center"><input type="text" name="total_price[]" class="total_price input-xsmall" value="<?php echo $value['total_price'];?>" readonly='readonly' /></td>
                        <td class="text-center"><a onclick="order_pro_delete('<?php echo $value['id'];?>','<?php echo $rslist_id['num'];?>')" href="javascript:;"><img src="tpl/www/images/del.gif" title="删除申报商品"></a></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php if(!$rslist){ ?>
                <script type="text/javascript">
                    $(document).ready(function(){
                        add_row();
                    });
                </script>
                <?php } ?>
                <div class="clearfix"></div>
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td class="text-right">申报价值：</td>
                        <td><input id="val" name="val" class="input-small" data-insure-percent="" value="<?php echo $rs['val'];?>" onkeyup="value=value.replace(/[^\d.]/g,'')"/> <span class="dj"></span>
                        </td>
                        <td class="text-right">税     费：</td>
                        <td colspan="5"><input id="tax" name="tax" class="input-small" value="<?php echo $rs['tax'];?>" onkeyup="value=value.replace(/[^\d.]/g,'')"/> <span class="dj"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">物品保价：</td>
                        <td><input id="safe" type="text" name="safe" value="<?php echo $rs['safe'];?>"  class="input-small" data-insure-percent="" /> <span class="dj"></span>
                        </td>
                        <td class="text-right">保价费用：</td>
                        <td><input id="safe_price" name="safe_price" class="input-small" value="<?php echo $rs['safe_price'];?>" readonly="" /> <span class="dj"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right" style="width:102px;">称重重量：</td>
                        <td><input name="jingzhong" id="jingzhong" value="<?php echo $rs['jingzhong'];?>" class="input-small" onkeyup="value=value.replace(/[^\d.]/g,'')"/> <span class="zl"></span>
                        </td>
                        <td class="text-right">预估重量：</td>
                        <td colspan="3"><input name="wt" id="wt" value="<?php echo $rs['weight'];?>" class="input-small" onkeyup="value=value.replace(/[^\d.]/g,'')"/> <span class="zl"></span>
                        </td>
                    </tr>
                    <tr class="hidden" id="vol_show">
                        <td class="text-right" style="width:102px;">体积：</td>
                        <td colspan="3"><input id="length" name="length" value="" class="input-small" onkeyup="value=value.replace(/[^\d.]/g,'')" placeholder="长"/> * <input id="width" name="width" value="" class="input-small" onkeyup="value=value.replace(/[^\d.]/g,'')"  placeholder="宽"> * <input id="height" name="height" value="" class="input-small" onkeyup="value=value.replace(/[^\d.]/g,'')" placeholder="高"/>
                            <input name="volume" id="volume" value="<?php echo $rs['volume'];?>" placeholder="体积重" readonly> <span class="zl"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right">备注：</td>
                        <td colspan="3"><textarea name="note" maxlength="100" style="width:100%;" rows="2"><?php echo $rs['note'];?></textarea>
                        </td>
                    </tr>
                    <?php if($custom){ ?>
                    <tr>
                        <td class="text-right">增值服务：</td>
                        <td colspan="3">
                            <div class="md-checkbox-inline">
                                <?php $custom_id["num"] = 0;$custom=is_array($custom) ? $custom : array();$custom_id["total"] = count($custom);$custom_id["index"] = -1;foreach($custom AS $key=>$value){ $custom_id["num"]++;$custom_id["index"]++; ?>
                                <div class="md-checkbox">
                                    <input class="fuwu" id="custom_<?php echo $value['id'];?>" value="<?php echo $value['id'];?>" name="custom[]" data-ordertype="<?php echo $value['price'];?>" type="checkbox" <?php if(in_array($value['id'],$custom_array)){ ?> checked<?php } ?>>
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
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bars"></i>支付方式</div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <input type="hidden" id="pay_price" name="pay_price" value="<?php echo $rs['price'];?>">
                        <input type="hidden" id="channel_price" name="channel_price" value="<?php echo $rs['channel_price'];?>">
                        <input type="hidden" id="custom_price" name="custom_price" value="<?php echo $rs['custom_price'];?>">
                        <input type="hidden" id="pay_weight" name="pay_weight" value="<?php echo $rs['pay_weight'];?>">
                        <td class="text-right">应付金额：</td>
                        <td class="bold font-red" id="sumprice"><?php echo $currency['symbol_left'];?><?php echo $rs['price'];?></td>
                    </tr>
                    <tr>
                        <td class="text-right">支付方式：</td>
                        <td>
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
                <div class="text-center">
                    <button class="btn blue" type="submit">扣 款 </button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<script type="text/javascript" src="<?php echo add_js('order.js');?>"></script>
<script type="text/javascript">
var is_id = '<?php echo $id;?>';
var pageurl = '<?php echo $pageurl;?>';
//收件人搜索
var highlightindex = -1; //高亮设置（-1为不高亮）
function AutoComplete(auto, search) {
    if ($("#" + search).val() != "") {
        var autoNode = $("#" + auto); //缓存对象（弹出框）
        var carlist = new Array();
        var n = 0;
        var mylist = [];
        var maxTipsCounts = 8 // 最大显示条数
        var user_id = $("#user_id").val();
        if(!user_id){
            $.dialog.alert(p_lang('未绑定会员账号'));
            return false;
        }
        var url = get_url('user','address','user_id='+user_id+'&fullname='+$("#" + search).val());
        var rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            mylist = rs.content;
            if (mylist == null) {
                autoNode.hide();
                return;
            }
            autoNode.empty(); //清空上次的记录
            for (i in mylist) {
                if (i < maxTipsCounts) {
                    var wordNode = mylist[i]; //弹出框里的每一条内容
                    var newDivNode = $("<div>").attr("id", i); //设置每个节点的id值
                    //$("#auto_div").css("width", $("#fullname").outerWidth(true) + 'px');
                    newDivNode.attr("style", "font:14px/25px arial;height:25px;padding:0 8px;cursor: pointer;");
                    newDivNode.html(wordNode.fullname+"|"+wordNode.mobile+"|"+wordNode.address).appendTo(autoNode); //追加到弹出框
                    newDivNode.attr("data_ct",wordNode.fullname+"|"+wordNode.idcard+"|"+wordNode.mobile+"|"+wordNode.county+wordNode.address+"|"+wordNode.id+"|"+wordNode.idcard_front+"|"+wordNode.idcard_back+"|"+wordNode.zipcode);
                    //鼠标移入高亮，移开不高亮
                    newDivNode.mouseover(function() {
                        if (highlightindex != -1) { //原来高亮的节点要取消高亮（是-1就不需要了）
                            autoNode.children("div").eq(highlightindex).css("background-color", "white");
                        }
                        //记录新的高亮节点索引
                        highlightindex = $(this).attr("id");
                        $(this).css("background-color", "#ebebeb");
                    });
                    newDivNode.mouseout(function() {
                        $(this).css("background-color", "white");
                    });
                    //鼠标点击文字上屏
                    newDivNode.click(function() {
                        //取出高亮节点的文本内容
                        var comText = autoNode.hide().children("div").eq(highlightindex).attr("data_ct");
                        highlightindex = -1;
                        //文本框中的内容变成高亮节点的内容
                        var strs= new Array();
                        strs=comText.split("|");
                        $("#" + search).val(strs[0]);
                        $("#idcard").val(strs[1]);
                        $("#mobile").val(strs[2]);
                        $("#address").val(strs[3]);
                        $("#zipcode").val(strs[7]);
                        var url = get_url('user','address_one','id='+strs[4]);
                        var rs = json_ajax(url);
                        if(rs.status == 'ok')
                        {
                            $("#prov").html(rs.content);
                        }
                        $("._e_upload").show();
                        $("#idcard_front").val("");
                        $("#idcard_back").val("");
                        $(".ttye").hide();
                        if(strs[5]!="" && strs[6]!=""){
                            $("._e_upload").hide();
                            $("#idcard_front").val(strs[5].replace(/(^\s*)|(\s*$)/g, ""));
                            $("#idcard_back").val(strs[6].replace(/(^\s*)|(\s*$)/g, ""));
                            $(".picshenfz").eq(0).append('<a class="ttye" href="javascript:void(0)" onclick="address_idcard('+strs[5].replace(/(^\s*)|(\s*$)/g, "")+');"><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-正面"></a>');
                            $(".picshenfz").eq(1).append('<a  class="ttye" href="javascript:void(0)" onclick="address_idcard('+strs[6].replace(/(^\s*)|(\s*$)/g, "")+');" ><img src="tpl/www/images/ico.png" width="16" height="13" title="预览身份证-反面"></a>');
                            $(".ttye").show();
                        }
                    })
                    if (mylist.length > 0) { //如果返回值有内容就显示出来
                        autoNode.show();
                    } else { //服务器端无内容返回 那么隐藏弹出框
                        autoNode.hide();
                        //弹出框隐藏的同时，高亮节点索引值也变成-1
                        highlightindex = -1;
                    }
                }
            }
        }
    }
}
document.onclick = function(e) {
    var e = e ? e : window.event;
    var tar = e.srcElement || e.target;
    if (tar.id != "fullname") {
        if ($("#auto_div").is(":visible")) {
            $("#auto_div").css("display", "none")
        }
    }
}
$("#fullname").keyup(function(event) {
    var myEvent = event || window.event;
    var keyCode = myEvent.keyCode;
    if (keyCode == 38 || keyCode == 40) {
        if (keyCode == 38) {
            var autoNodes = $("#auto_div").children("div");
            if (highlightindex != -1) {
                autoNodes.eq(highlightindex).css("background-color", "white");
                highlightindex--;
            } else {
                highlightindex = autoNodes.length - 1;
            }
            if (highlightindex == -1) {
                highlightindex = autoNodes.length - 1;
            }
            autoNodes.eq(highlightindex).css("background-color", "#ebebeb");
        }
        if (keyCode == 40) {
            var autoNodes = $("#auto_div").children("div");
            if (highlightindex != -1) {
                autoNodes.eq(highlightindex).css("background-color", "white");
            }
            highlightindex++;
            if (highlightindex == autoNodes.length) {
                highlightindex = 0;
            }
            autoNodes.eq(highlightindex).css("background-color", "#ebebeb");
        }
    } else if (keyCode == 13) {
        if (highlightindex != -1) {
            var comText = $("#auto_div").hide().children("div").eq(highlightindex).text();

            highlightindex = -1;
            $("#fullname").val(comText);
            if ($("#auto_div").is(":visible")) {
                $("#auto_div").css("display", "none")
            }
        }
        //checkInput();
    } else {
        if ($("#fullname").val() == "") {
            $("#auto_div").hide();
        } else {
            AutoComplete("auto_div", "fullname");
        }
    }
});
function save_order()
{
    $("#ordersave").ajaxSubmit({
        'url':get_url('order','pay_save'),
        'type':'post',
        'dataType':'json',
        'success':function(rs){
            //运单状态为否时
            if(rs.status != 'ok'){
                if(!rs.content){
                    rs.content = '提交失败';
                }
                $.dialog.alert(rs.content);
                return false;
            }
            //运单操作成功的提示
            if(is_id && is_id != '0'){
                var tips = '运单：<span class="font-red"><?php echo $rs['sn'];?></span>扣款成功';
                $.dialog.alert(tips,function(){
                    $.dsy.go(pageurl);
                },'succeed');
            }
        }
    });
}
function sumprice(){
    var symbol_left = "<?php echo $currency['symbol_left'];?>";
    try{
        var stock_id = $('#stock').val();
        var cycost = $("#channel").val();
        console.log("开始执行");
        if(cycost){
            var cyarr = cycost.split("|");
            var cyjingzhong = $("#jingzhong").val();
            var volume = $("#volume").val();
            if(volume>cyjingzhong) cyjingzhong = volume;
            var cytax=$('#tax').val();
            var cysafe_price=$('#safe_price').val();
            var cty=0;
            if(cyarr[1]!="0.00"){				if(parseFloat('0'+cyjingzhong.substring(cyjingzhong.indexOf('.')))<=parseFloat(cyarr[1])){					cyjingzhong=parseInt(cyjingzhong);				}else{					cyjingzhong=parseFloat(cyjingzhong);				}			}
            if(cyarr[2]=='ceil'){cyjingzhong=Math.ceil(cyjingzhong);}
            /*if(cyarr[2]=='round'){
                if(parseInt(cyjingzhong)==parseFloat(cyjingzhong)){
                    cyjingzhong = parseInt(cyjingzhong);
                }else{
                    var fn=parseFloat('0'+cyjingzhong.substring(cyjingzhong.indexOf('.')));
                    if(fn >0 && fn < 0.5){
                        cyjingzhong = parseInt(cyjingzhong)+0.5;
                    }else if(fn > 0.5){
                        cyjingzhong = parseInt(cyjingzhong)+1;
                    }
                }
            }*/			if(cyarr[2]=='round'){				var fn=parseFloat('0'+cyjingzhong.substring(cyjingzhong.indexOf('.')));				if(fn>0.5){					if(parseFloat(fn-0.5)>cyarr[1]){						cyjingzhong = parseInt(cyjingzhong)+1;					}else{						cyjingzhong = parseInt(cyjingzhong)+0.5;					}				}else{					if(fn>cyarr[1]){						cyjingzhong = parseInt(cyjingzhong)+0.5;					}else{						cyjingzhong = parseInt(cyjingzhong);					}				}			}
            if(cyarr[2]=='intval'){cyjingzhong=parseFloat(cyjingzhong);}
            $("#pay_weight").val(cyjingzhong);
            if(cyjingzhong > cyarr[3]){
                cty = parseFloat(cyarr[4]) + parseFloat(cyarr[5]) * parseFloat((parseFloat(cyjingzhong) - 1));
            }else{
                cty = parseFloat(cyarr[4]) + parseFloat(cyarr[5]) * parseFloat((parseFloat(cyarr[3]) - 1));
            }
            $("#channel_price").val(cty.toFixed(2));
            cty+= parseFloat(cytax);
            cty+= parseFloat(cysafe_price);
            var other_price = cty;
            $(".fuwu").each(function(){
                if(this.checked){
                    var dd=$(this).attr("data-ordertype");
                    if(dd!=undefined)
                        cty+= parseFloat(dd);
                }
            });
            $("#custom_price").val(cty-other_price);
            $("#sumprice").html(symbol_left+cty.toFixed(2));
            $("#pay_price").val(cty.toFixed(2));
        }
    }catch(e){
        console.log(e.message);
    }
}
$(document).ready(function(){
    $("#ordersave").submit(function(){
        var jingzhong = $("#jingzhong").val();
        if(!jingzhong){
            $.dialog.alert('请填写包裹实际称重重量');
            return false;
        }
        save_order();
        return false;
    });
    var cost,arr,url,rs,stock_id;
    if(is_id && is_id != '0'){
        stock_id = $('#stock').val();
        cost = $("#channel").val();
        arr = cost.split("|");
        $(".zl").text(arr[7]);
        if(arr[13]!=0){
            $('#vol_show').removeClass('hidden');
        }else{
            $('#vol_show').addClass('hidden');
        }
        $('#safe').attr('data-insure-percent',arr[8]);
        $('#val').attr('data-insure-percent',arr[9]);
        url = get_url('package','currency','id='+arr[6]);
        rs = json_ajax(url);
        if(rs.status == 'ok')
        {
            $("#currency").val(rs.content.currency);
            $(".dj").text(rs.content.currency);
        }
        $("#channel").change(function(){
            cost = $("#channel").val();
            if(!cost){
                $.dialog.alert("请选择发货渠道");
            }else{
                arr = cost.split("|");
                $(".zl").text(arr[7]);
                if(arr[13]!=0){
                    $('#vol_show').removeClass('hidden');
                }else{
                    $('#vol_show').addClass('hidden');
                }
                $('#safe').attr('data-insure-percent',arr[8]);
                $('#val').attr('data-insure-percent',arr[9]);
                //先填商品，再选渠道
                if($('#val').val()){
                    $('[name="tax"]').val(parseFloat($('#val').val(), 10)*$('#val').attr('data-insure-percent')*0.01);
                }
                if($('#safe').val()){
                    $('[name="safe_price"]').val(parseFloat($('#safe').val(), 10)*$('#safe').attr('data-insure-percent')*0.01);
                }
                url = get_url('package','currency','id='+arr[6]);
                rs = json_ajax(url);
                if(rs.status == 'ok')
                {
                    $("#currency").val(rs.content.currency);
                    $(".dj").text(rs.content.currency);
                    sumprice();
                }
            }
        });
    }else{
        $("#channel").change(function(){
            cost = $("#channel").val();
            if(!cost){
                $.dialog.alert("请选择发货渠道");
            }else{
                arr = cost.split("|");
                $(".zl").text(arr[7]);
                if(arr[13]!=0){
                    $('#vol_show').removeClass('hidden');
                }else{
                    $('#vol_show').addClass('hidden');
                }
                $('#safe').attr('data-insure-percent',arr[8]);
                $('#val').attr('data-insure-percent',arr[9]);
                url = get_url('package','currency','id='+arr[6]);
                rs = json_ajax(url);
                if(rs.status == 'ok')
                {
                    $("#currency").val(rs.content.currency);
                    $(".dj").text(rs.content.currency);
                    sumprice();
                }
            }
        });
    }
});
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
//------------------------------------------------申报价格
$('[name="val"]').bind('keyup', function(){
    var all_price = 0;
    $('#prolist tr').each(function(){

        var count = parseInt($(this).find('.count').val(), 10),unit_price = parseFloat($(this).find('.unit_price').val(), 10);

        if (count && unit_price) all_price += count*unit_price;

    });

    if (all_price < parseFloat($(this).val(), 10))
    {
        alert('申报价值不能超过商品总价');
        $(this).val('');
    }
    var tax = $(this).attr('data-insure-percent');
    if (tax)
    {
        $('[name="tax"]').val(parseFloat($(this).val(), 10)*tax*0.01);
        sumprice();
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

function setProcuctPrice(self)
{
    var obj = self.parents('tr');

    var count = parseInt(obj.find('.count').val(), 10),unit_price = parseFloat(obj.find('.unit_price').val(), 10);

    if (count && unit_price)
    {
        obj.find('.total_price').val(count*unit_price);
    }
    var max = 0;
    $('#prolist tbody tr').each(function(){
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
        sumprice();
    }
}
function setvol(){
    var channel = $("#channel").val();
    if(channel){
        var channel_arr = channel.split("|");
    }
    var max = 0;
    var length = parseFloat($("#length").val());
    var width = parseFloat($("#width").val());
    var height = parseFloat($("#height").val());
    if (length && width && height)
    {
        max = max + length*width*height;
    }
    var volume = max/channel_arr[13];
    $("#volume").val(volume.toFixed(2));
    sumprice();
}
$('#prolist').delegate('.count', 'keyup', function(){
    setProcuctPrice($(this));
});

$('#prolist').delegate('.unit_price', 'keyup', function(){
    setProcuctPrice($(this));
});
$('[name="jingzhong"]').bind('keyup', function(){sumprice();});
$('[name="tax"]').bind('keyup', function(){sumprice();});
$('#length').bind('keyup', function(){setvol();});
$('#width').bind('keyup', function(){setvol();});
$('#height').bind('keyup', function(){setvol();});
$('#ucode').blur(function(){
    var ucode = $('#ucode').val();
    if(!ucode){
        $.dialog.alert("请填写会员编号",function(){
            $('#ucode').focus();
        },'succeed');
        return false;
    }
    var url = get_url('user','get_user','ucode='+ucode);
    var rs = json_ajax(url);
    if(rs.status == 'ok')
    {
        $('#user_id').val(rs.content.id);
        $("#show").html('<span class="font-red">'+rs.content.email+'/'+rs.content.user+'</span>');
    }
})
function address_idcard(id)
{
    var url = get_url('user','address_idcard','id='+id);
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
$(".fuwu").click(function(){
    sumprice();
});
</script>
<?php $this->output("foot","file"); ?>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","包裹预报"); ?><?php $this->output("head_member","file"); ?>
<?php $this->output("nav","file"); ?>
<div class="page-container">
    <?php $this->output("block_usercp","file"); ?>
    <div class="page-content-wrapper">
        <div class="page-content">
            <form method="post" id="forecast_submit">
                <?php if($id){ ?><input type="hidden" name="id" value="<?php echo $id;?>" /><?php } ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet box grey">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-bars"></i><?php if($id){ ?>编辑包裹信息<?php } else { ?>预报新包裹<?php } ?></div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td class="text-right">到货仓库：<span class="font-red">*</span></td>
                                        <td>
                                            <select class="form-control" id="stock" name="stock">
                                                <option value="">请选择到货仓库</option>
                                                <?php $stock_list_id["num"] = 0;$stock_list=is_array($stock_list) ? $stock_list : array();$stock_list_id["total"] = count($stock_list);$stock_list_id["index"] = -1;foreach($stock_list AS $key=>$value){ $stock_list_id["num"]++;$stock_list_id["index"]++; ?>
                                                <option value="<?php echo $value['id'];?>"<?php if($stock == $value['id']){ ?> selected<?php } ?>>&nbsp;&nbsp;<?php echo $value['title'];?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">包裹单号：<span class="font-red">*</span></td>
                                        <td><input id="sn" name="sn" value="<?php echo $res['sn'];?>" type="text" onkeyup="value=value.replace(/[^\w\/]/ig,'')" style="width:100%;"/>
                                            <span class="help-block">购物网站寄出发往仓库的包裹运单号，建议直接从购物网站复制避免填写错误</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">快递公司：</td>
                                        <td>
                                            <select class="form-control" name="express">
                                                <option value="">请选择快递公司</option>
                                                <?php $express_id["num"] = 0;$express=is_array($express) ? $express : array();$express_id["total"] = count($express);$express_id["index"] = -1;foreach($express AS $key=>$value){ $express_id["num"]++;$express_id["index"]++; ?>
                                                <option value="<?php echo $value;?>"<?php if($res['express']==$value){ ?> selected<?php } ?>>&nbsp;&nbsp;<?php echo $value;?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-right">备注：</td>
                                        <td><textarea name="note" maxlength="100" style="width:100%;" rows="2"><?php echo $res['note'];?></textarea>
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
                                    <i class="fa fa-bars"></i>物品信息</div>
                            </div>
                            <div class="portlet-body">
                                <input id="pro_cate" value="<?php $prolist_id["num"] = 0;$prolist=is_array($prolist) ? $prolist : array();$prolist_id["total"] = count($prolist);$prolist_id["index"] = -1;foreach($prolist AS $key=>$value){ $prolist_id["num"]++;$prolist_id["index"]++; ?>&lt;option value=&quot;<?php echo $value['title'];?>&quot;&gt;&nbsp;<?php echo $value['title'];?>&nbsp;&lt;/option&gt;<?php } ?>" type="hidden">
                                <div class="table-scrollable">
                                <table class="table table-striped table-bordered table-advance table-hover" id="tab">
                                    <thead>
                                    <tr>
                                        <?php if($prolist){ ?><th class="bold text-center">类别</th><?php } ?>
                                        <th class="bold text-center">品牌<span class="font-red">*</span></th>
                                        <th class="bold text-center">物品名称<span class="font-red">*</span></th>
                                        <th class="bold text-center">规格</th>
                                        <th class="bold text-center">数量<span class="font-red">*</span></th>
                                        <th class="bold text-center">单价<span class="font-red">*</span></th>
                                        <th class="bold text-center">合计<span class="font-red">*</span></th>
                                        <th class="text-center bold">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(!$id){ ?>
                                    <tr>
                                        <?php if($prolist){ ?><td><select class="input-small" name='catename[]' id="catename"></select></td><?php } ?>
                                        <td><input class="input-small" name="brand[]" type="text" placeholder="品牌" required=""></td>
                                        <td><input class="input-small" name="title[]" type="text" placeholder="物品" required=""></td>
                                        <td><input class="input-small" name="size[]" type="text" placeholder="规格"></td>
                                        <td><input style="width: 60px;" class="count" name="qty[]" type="text" placeholder="数量" required="" onkeyup="value=value.replace(/[^\d]/g,'')"></td>
                                        <td><input style="width: 60px;" class="unit_price" name="price[]" type="text" placeholder="单价" required="" onkeyup="value=value.replace(/[^\d.]/g,'')"></td>
                                        <td><input style="width: 60px;" class="price" name="total_price[]" type="text" placeholder="合计" readonly='readonly'></td>
                                        <td class="text-center"><a id="addItem" href="javascript:;"><img src="tpl/www/images/add.gif" alt="增加" title="增加"></a></td>
                                    </tr>
                                    <?php } else { ?>
                                    <?php if($rslist){ ?>
                                    <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                                    <tr id="<?php echo $key+1;?>">
                                        <input type="hidden" name="pro_id[]" value="<?php echo $value['id'];?>" />
                                        <?php if($value['catename']){ ?><td><input class="input-small" name="catename[]" type="text" value="<?php echo $value['catename'];?>" required=""></td><?php } ?>
                                        <td><input class="input-small" name="brand[]" type="text" value="<?php echo $value['brand'];?>" required=""></td>
                                        <td><input id="pro_title_<?php echo $key+1;?>" class="input-small" name="title[]" type="text" value="<?php echo $value['title'];?>" required=""></td>
                                        <td><input class="input-small" name="size[]" type="text" value="<?php echo $value['size'];?>"></td>
                                        <td><input style="width: 60px;" class="count" name="qty[]" type="text" value="<?php echo $value['qty'];?>" required="" onkeyup="value=value.replace(/[^\d]/g,'')"></td>
                                        <td><input style="width: 60px;" class="unit_price" name="price[]" type="text" value="<?php echo $value['price'];?>" required="" onkeyup="value=value.replace(/[^\d.]/g,'')"></td>
                                        <td><input style="width: 60px;" class="price" name="total_price[]" type="text" value="<?php echo $value['total_price'];?>" readonly='readonly'></td>
                                        <td class="text-center"><?php if($key==0){ ?><a id="addItem" href="javascript:;"><img src="tpl/www/images/add.gif" alt="增加" title="增加"></a><?php } else { ?><a href='<?php echo dsy_url(array('ctrl'=>'package','func'=>'forecast'));?>&id=<?php echo $id;?>###' onclick='package_pro_delete("<?php echo $value['id'];?>","<?php echo $key+1;?>")'><img src='tpl/www/images/del.gif'></a><?php } ?></td>
                                    </tr>
                                    <?php } ?>
                                    <?php } else { ?>
                                    <tr>
                                        <input name="pro_id[]" value="add" type="hidden">
                                        <?php if($prolist){ ?><td><select class="input-small" name='catename[]' id="catename"></select></td><?php } ?>
                                        <td><input class="input-small" name="brand[]" type="text" placeholder="英文品牌" value="" required=""></td>
                                        <td><input class="input-small" name="title[]" type="text" placeholder="物品名称" required=""></td>
                                        <td><input class="input-small" name="size[]" type="text" placeholder="多少克/粒/型号" value="" ></td>
                                        <td><input class="count input-xsmall" name="qty[]" type="text" placeholder="数量" required="" onkeyup="value=value.replace(/[^\d]/g,'')"></td>
                                        <td><input class="unit_price input-xsmall" name="price[]" type="text" placeholder="单价" required="" onkeyup="value=value.replace(/[^\d.]/g,'')"></td>
                                        <td><input class="price input-xsmall" name="total_price[]" type="text" placeholder="合计" readonly='readonly'></td>
                                        <td class="text-center"><a id="addItem" href="javascript:;"><img src="tpl/www/images/add.gif" alt="增加" title="增加"></a></td>
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
                                            html +="<td><input class='input-small' name='brand[]' type='text' placeholder='品牌' required=''></td>";
                                            html +="<td><input class='input-small' name='title[]' type='text' placeholder='物品' required=''></td>";
                                            html +="<td><input class='input-small' name='size[]' type='text' placeholder='规格'></td>";
                                            html +="<td><input style='width: 60px;' class='count' name='qty[]' type='text' placeholder='数量' required='' onkeyup=value=value.replace(/[^\\d]/g,'')></td>";
                                            html +="<td><input style='width: 60px;' class='unit_price' name='price[]' type='text' placeholder='单价' required='' onkeyup=value=value.replace(/[^\\d.]/g,'')></td>";
                                            html +="<td><input style='width: 60px;' class='price' name='total_price[]' type='text' placeholder='合计' readonly='readonly'></td>";
                                            if(!is_id){
                                                html +="<td><a href='<?php echo dsy_url(array('ctrl'=>'package','func'=>'forecast'));?>###' onclick='delItem("+_len+")'><img src='tpl/www/images/del.gif'></a></td></tr>";
                                            }else{
                                                html +="<input type='hidden' name='pro_id[]' value='add'/>";
                                                html +="<td><a href='<?php echo dsy_url(array('ctrl'=>'package','func'=>'forecast'));?>&id="+is_id+"###' onclick='delItem("+_len+")'><img src='tpl/www/images/del.gif'></a></td></tr>";
                                            }
                                            $("#tab").append(html);
                                        })
                                    })
                                    //删除<tr/>
                                    function delItem(num) {
                                        $("tr[id='"+num+"']").remove();//删除当前行

                                        return false;
                                    }
                                    //删除订单产品，删除操作成功会更新订单金额
                                    function package_pro_delete(id,numid)
                                    {
                                        var title = $("#pro_title_"+numid).val();
                                        $.dialog.confirm("确定要删除物品：<span class='darkblue'>"+title+"</span>",function(){
                                            var url = get_url('package','product_delete','id='+id);
                                            var rs = json_ajax(url);
                                            if(rs.status == 'ok')
                                            {
                                                $.dialog.alert("物品：<span class='darkblue'>"+title+"</span> 已成功删除！",function(){
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
                                        <td class="text-right" style="width:102px;">预估重量：</td>
                                        <td><input id="wt" name="wt" class="input-small" value="<?php echo $res['wt'];?>" onkeyup="value=value.replace(/[^\d.]/g,'')"/> LB
                                        </td>
                                    </tr>
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
                            提 交
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var is_id = '<?php echo $id;?>';
    $(document).ready(function(){
        $("#forecast_submit").submit(function(){
            $(this).ajaxSubmit({
                type:'post',
                url: api_url('package','forecast'),
                dataType:'json',
                success: function(rs){
                    if(rs.status != 'ok'){
                        if(!rs.content){
                            rs.content = '提交失败';
                        }
                        $.dialog.alert(rs.content);
                        return false;
                    }
                    //订单操作成功的提示
                    if(is_id && is_id != '0'){
                        $.dialog.alert('包裹：<span class="font-red"><?php echo $res['sn'];?></span> 编辑成功',function(){
                            $.dsy.go('<?php echo dsy_url(array('ctrl'=>'package','func'=>'index'));?>');
                        },'succeed');
                    }else{
                        $.dialog.alert('包裹预报成功',function(){
                            $.dsy.reload();
                        },'succeed');
                    }
                }
            });
            return false;
        });
        $("#stock").change(function(){
            cost = $("#stock").val();
            if(!cost){
                $.dialog.alert("请选择仓库");
            }
        });
    });
    function setProcuctPrice(self)
    {
        var obj = self.parents('tr');
        var count = parseInt(obj.find('.count').val(), 10),unit_price = parseFloat(obj.find('.unit_price').val(), 10);
        if (count && unit_price)
        {
            obj.find('.price').val(count*unit_price);
           //计算申报价值
             /*var max = 0;
             $('#tab tbody tr').each(function(){
             var count = parseInt($(this).find('.count').val(), 10),
             unit_price = parseFloat($(this).find('.unit_price').val(), 10);
             //console.log($(this).find('.count').val());
             max = max + count*unit_price;
             });
             $('[name="val"]').val(max);*/
        }
    }
    /*function setProcuctWeight(self)
    {
        var all = 0;
        $('#tab tr').each(function(){
            var weight = parseFloat($(this).find('.weight').val(), 10),count = parseInt($(this).find('.count').val(), 10);
            if(weight && count) all = all + count*weight;
        });
        $('[name="wt"]').val(all);
    }*/
    $('#tab').delegate('.count', 'keyup', function(){
        setProcuctPrice($(this));
        //setProcuctWeight($(this));
    });
    $('#tab').delegate('.unit_price', 'keyup', function(){
        setProcuctPrice($(this));
    });
</script>
<?php $this->output("foot_member","file"); ?>
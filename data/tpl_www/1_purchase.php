<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","代购商品"); ?><?php $this->output("head_member","file"); ?>
<?php $this->output("nav","file"); ?>
<div class="page-container">
    <?php $this->output("block_usercp","file"); ?>
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet box grey">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-bars"></i><?php if($id){ ?>编辑<?php } else { ?>添加<?php } ?>代购商品</div>
                            <div class="text-right" style="margin-top:4px;">
                                <a class="btn green" href="javascript:show();void(0)">查看可代购的商城</a>
                            </div>
                        </div>

                        <div class="portlet-body">
                            <div class="tabbable tabbable-tabdrop">
                                <ul class="nav nav-tabs" style="margin-bottom:0px;">
                                    <li class="<?php if($sys['func'] == 'add'){ ?>active <?php } ?>bold">
                                        <a href="javascript:void(0)" onclick="tab('add')">添加代购</a>
                                    </li>
                                    <li class="<?php if($sys['func'] == 'index'){ ?>active <?php } ?>bold">
                                        <a href="javascript:void(0)" onclick="tab('index')">我的代购</a>
                                    </li>
                                </ul>
                                <div class="tab-content" style="border: 1px solid #ddd;border-top: none;padding:10px;">

                                    <div class="tab-pane active">
                                        <?php if($sys['func'] == 'add'){ ?>
                                        <form method="post" id="do_submit">
                                            <?php if($id){ ?><input type="hidden" name="id" value="<?php echo $id;?>" /><?php } ?>
                                            <table class="table table-striped table-bordered table-hover">
                                                <tbody>
                                                <tr>
                                                    <td class="text-right col-md-2">网址：</td>
                                                    <td class="col-md-10"><input type="text" id="url" name="url" value="<?php echo $rs['url'];?>" style="width:100%;" placeholder="http://" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">类别：</td>
                                                    <td>
                                                        <select class="form-control" id="catename" name="catename">
                                                            <option value="">请选择商品类别</option>
                                                            <?php $catelist_id["num"] = 0;$catelist=is_array($catelist) ? $catelist : array();$catelist_id["total"] = count($catelist);$catelist_id["index"] = -1;foreach($catelist AS $key=>$value){ $catelist_id["num"]++;$catelist_id["index"]++; ?>
                                                            <option value="<?php echo $value['title'];?>" <?php if($rs['catename']==$value['title']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">商品名称：</td>
                                                    <td><input type="text" id="title" name="title" value="<?php echo $rs['title'];?>" style="width:100%;" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">单价：</td>
                                                    <td><input type="text" id="price" name="price" value="<?php echo $rs['price'];?>" style="width:100%;" onkeyup="value=value.replace(/[^\d.]/g,'')" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">数量：</td>
                                                    <td><input type="text" id="num" name="num" value="<?php echo $rs['num'];?>" style="width:100%;" onkeyup="value=value.replace(/[^\d]/g,'')" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">规格：</td>
                                                    <td><input type="text" id="size" name="size" value="<?php echo $rs['size'];?>" style="width:100%;" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">颜色：</td>
                                                    <td><?php echo $extlist['html'];?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">到货仓库：</td>
                                                    <td>
                                                        <select class="form-control" id="stock" name="stock">
                                                            <option value="">请选择发货仓库</option>
                                                            <?php $stock_id["num"] = 0;$stock=is_array($stock) ? $stock : array();$stock_id["total"] = count($stock);$stock_id["index"] = -1;foreach($stock AS $key=>$value){ $stock_id["num"]++;$stock_id["index"]++; ?>
                                                            <option value="<?php echo $value['id'];?>" <?php if($rs['stock'] ? $rs['stock'] : $user['stock_id'] == $value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">备注：</td>
                                                    <td>
                                                        <textarea id="note" name="note" maxlength="100" style="width:100%;" rows="2"><?php echo $rs['note'];?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">运费：</td>
                                                    <td><input type="text" id="express_price" name="express_price" value="<?php echo $rs['express_price'];?>" style="width:100%;" /></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">费用合计：</td>
                                                    <td class="font-red bold"><input type="hidden" id="pay_price" name="pay_price" value="<?php echo $rs['pay_price'];?>" style="width:100%;" />$<span id="total_price"> <?php echo $rs['pay_price'];?></span></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <button class="btn blue" type="submit">
                                                        <i class="fa fa-edit"></i>
                                                        提 交
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <?php } ?>
                                        <?php if($sys['func'] == 'index'){ ?>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="bold text-center">商品缩略图</th>
                                                    <th class="bold text-center">商品名称</th>
                                                    <th class="bold text-center">单价</th>
                                                    <th class="bold text-center">数量</th>
                                                    <th class="bold text-center">备注</th>
                                                    <th class="bold text-center">到货仓库</th>
                                                    <th class="bold text-center">提交时间</th>
                                                    <th class="bold text-center">状态</th>
                                                    <th class="bold text-center">运费（$）</th>
                                                    <th class="bold text-center">总费用（$）</th>
                                                    <th class="bold text-center">操作</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if($rslist){ ?>
                                                <?php $rslist_id["num"] = 0;$rslist=is_array($rslist) ? $rslist : array();$rslist_id["total"] = count($rslist);$rslist_id["index"] = -1;foreach($rslist AS $key=>$value){ $rslist_id["num"]++;$rslist_id["index"]++; ?>
                                                <tr>
                                                    <td class="text-center"><?php if($value['vpic']<>''){ ?><a href="javascript:show_pic('<?php echo $value['vpic'];?>');void(0);"><img src="tpl/www/images/ico.png" width="16" height="13"></a><?php } ?></td>
                                                    <td><a href="<?php echo $value['url'];?>" target="_blank"><?php echo $value['title'];?></a></td>
                                                    <td class="text-center"><?php echo $value['price'];?></td>
                                                    <td class="text-center"><?php echo $value['num'];?></td>
                                                    <td><?php echo $value['note'];?></td>
                                                    <td><?php echo $value['stock']['title'];?></td>
                                                    <td class="text-center"><?php echo date('Y-m-d',$value['addtime']);?></td>
                                                    <td class="text-center"><?php if($value['status']==2){ ?>已订购<?php }elseif($value['status']==1){ ?><span class="font-red">已扣款</span><?php } else { ?><span class="font-blue">未审核</span><?php } ?></td>
                                                    <td class="text-center"><?php echo $value['express_price'];?></td>
                                                    <td class="text-center"><?php echo $value['pay_price'];?></td>
                                                    <td class="text-center">
                                                        <?php if($value['status']==0){ ?>
                                                        <input type="button" value="修改" onclick="edit(<?php echo $value['id'];?>)" class="btn btn-xs btn-info" />
                                                        <input type="button" value="删除" onclick="del(<?php echo $value['id'];?>)" class="btn btn-xs btn-danger" />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                <?php } else { ?>
                                                <tr><td colspan="11"><span class="fa fa-warning">没有记录!</span></td></tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row">
                                            <div class="text-right" style="margin-right: 16px;"><?php $this->output("block_pagelist","file"); ?></div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var is_id = '<?php echo $id;?>';
    $(document).ready(function(){
        $("#do_submit").submit(function(){
            var url = $("#url").val();
            if(!url){
                $.dialog.alert("网址不能为空！");
                return false;
            }
            var title = $("#title").val();
            if(!title){
                $.dialog.alert("商品名称不能为空");
                return false;
            }
            var price = $("#price").val();
            if(!price){
                $.dialog.alert("商品单价不能为空");
                return false;
            }
            var num = $("#num").val();
            if(!num){
                $.dialog.alert("商品数量不能为空");
                return false;
            }
            var stock = $("#stock").val();
            if(!stock){
                $.dialog.alert("请选择到货仓库");
                return false;
            }
            $(this).ajaxSubmit({
                type:'post',
                url: get_url('purchase','save'),
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
                        $.dialog.alert('代购提交成功',function(){
                            $.dsy.go('<?php echo dsy_url(array('ctrl'=>'purchase','func'=>'index'));?>');
                        },'succeed');
                    }else{
                        $.dialog({
                            content: '代购提交成功！',
                            button: [
                                {
                                    name: '继续提交',
                                    callback: function () {
                                        $.dsy.reload();
                                    },
                                    focus: true
                                },
                                {
                                    name: '回到代购列表',
                                    callback: function () {
                                        $.dsy.go('<?php echo dsy_url(array('ctrl'=>'purchase','func'=>'index'));?>');
                                    }
                                }
                            ]
                        });
                    }
                }
            });
            return false;
        });
    });
    var url;
    function tab(arg){
        var url = get_url('purchase',arg);
        $.dsy.go(url);
    }
    function edit(id){
        $.dsy.go("<?php echo dsy_url(array('ctrl'=>'purchase','func'=>'add'));?>&id="+id);
    }
    function del(id){
        $.dialog.confirm('确定要删除代购信息吗?<br />删除后您不能再恢复!',function(){
            var url = get_url('purchase','delete','id='+id);
            var rs = json_ajax(url);
            if(rs.status == 'ok')
            {
                $.dsy.reload();
            }
            else
            {
                $.dialog.alert(rs.content);
                return false;
            }
        });
    }
    function show()
    {
        var url = get_url('purchase','web');
        $.dialog.open(url,{
            title: "可代购商城网站",
            lock : true,
            width: "90%",
            height: "90%",
            resize: false,
            'cancel':function(){
                return true;
            }
        });
    }
    function show_pic(id)
    {
        var url = get_url('purchase','show_pic','id='+id);
        var title = '商品图片';
        $.dialog.open(url,{
            'title':title,
            'lock':true,
            'width':'350px',
            'height':'350px',
            'cancel':function(){
                return true;
            }
        });
    }
    function sumprice(){
        var num = parseFloat($('#num').val());
        var price = parseFloat($("#price").val());
        var express_price = parseFloat($("#express_price").val());
        var max = 0;
        if(!num) num = 0;
        if(!price) price = 0;
        if(!express_price) express_price = 0;
        max = max + num*price+express_price;
        $("#total_price").html(max.toFixed(2));
        $("#pay_price").val(max.toFixed(2));
    }
    $('#num').bind('keyup', function(){sumprice();});
    $('#price').bind('keyup', function(){sumprice();});
    $('#express_price').bind('keyup', function(){sumprice();});
</script>
<?php $this->output("foot_member","file"); ?>
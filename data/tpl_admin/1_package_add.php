<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo dsy_url(array('ctrl'=>'package'));?>">包裹管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>添加包裹</span>
        </li>
    </ul>
</div>
<form method="post" id="ordersave">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right"><span class="font-red">*</span>到货仓库：</td>
                            <td>
                                <select class="form-control" id="stock" name="stock">
                                    <?php $stocks_id["num"] = 0;$stocks=is_array($stocks) ? $stocks : array();$stocks_id["total"] = count($stocks);$stocks_id["index"] = -1;foreach($stocks AS $key=>$value){ $stocks_id["num"]++;$stocks_id["index"]++; ?>
                                    <option value="<?php echo $value['id'];?>"<?php if($rs['stock']==$value['id']){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right"><span class="font-red">*</span>会员编号：</td>
                            <td>
                                <input type="hidden" id="user_id" name="user_id" value="">
                                <input type="text" id="ucode" name="ucode" value="<?php echo $rs['ucode'];?>" class="form-control"/>
                                <span id="show" class="help-block"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right"><span class="font-red">*</span>收件姓名：</td>
                            <td><input type="text" id="fullname" name="fullname" value="<?php echo $rs['fullname'];?>" class="form-control"/></td>
                        </tr>
                        <tr>
                            <td class="text-right"><span class="font-red">*</span>仓位：</td>
                            <td><input type="text" id="position" name="position" value="<?php echo $rs['position'];?>" class="form-control"/></td>
                        </tr>
                        <tr>
                            <td class="text-right"><span class="font-red">*</span>重量：</td>
                            <td><input id="jingzhong" name="jingzhong"  value="<?php echo $rs['jingzhong'];?>" onkeyup="value=value.replace(/[^\d.]/g,'')" class="form-control" placeholder="LB"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">快递公司：</td>
                            <td>
                                <select class="form-control" name="express">
                                    <option value="">请选择快递公司</option>
                                    <?php $express_id["num"] = 0;$express=is_array($express) ? $express : array();$express_id["total"] = count($express);$express_id["index"] = -1;foreach($express AS $key=>$value){ $express_id["num"]++;$express_id["index"]++; ?>
                                    <option value="<?php echo $value;?>"<?php if($rs['express']==$value){ ?> selected<?php } ?>><?php echo $value;?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">备注：</td>
                            <td><textarea name="note" maxlength="100" rows="2" class="form-control"><?php echo $rs['note'];?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right col-md-2"><span class="font-red">*</span>包裹单号：</td>
                            <td><input type="text" id="sn" name="sn" value="<?php echo $rs['sn'];?>" class="form-control"/></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript" src="<?php echo add_js('order.js');?>"></script>
<script type="text/javascript">
    function save_order()
    {
        var fullname = $("#fullname").val();
        if(!fullname){
            $.dialog.alert("请填写收件姓名",function(){
                $('#fullname').focus();
            },'succeed');
            return false;
        }
        var position = $("#position").val();
        if(!position){
            $.dialog.alert("请填写仓位",function(){
                $('#position').focus();
            },'succeed');
            return false;
        }
        var jingzhong = $("#jingzhong").val();
        if(!jingzhong){
            $.dialog.alert("请填写入库重量",function(){
                $('#jingzhong').focus();
            },'succeed');
            return false;
        }
        $("#ordersave").ajaxSubmit({
            'url':get_url('package','package_save'),
            'type':'post',
            'dataType':'json',
            'success':function(rs){
                //订单状态为否时
                if(rs.status != 'ok'){
                    if(!rs.content){
                        rs.content = '提交失败';
                    }
                    $.dialog.alert(rs.content);
                    return false;
                }else{
                    $.dialog.alert("包裹添加成功",function(){
                        $.dsy.go('<?php echo dsy_url(array('ctrl'=>'package','func'=>'add'));?>');
                    },'succeed');
                }
            }
        });
    }
    $(document).ready(function(){
        $("#sn").keyup(function(event){
            var sn = $("#sn").val();
            if(!sn){
                $.dialog.alert('包裹单号不能为空',function(){
                    $("#sn").focus();
                },'succeed');
                return false;
            }
            if(event.keyCode == 13){
                save_order();
                return false;
            }
        });
    });
    $('#ucode').blur(function(){
        var ucode = $('#ucode').val();
		if(ucode){
			var url = get_url('user','get_user','ucode='+ucode);
			var rs = json_ajax(url);
			if(rs.status == 'ok')
			{
				$('#user_id').val(rs.content.rs.id);
				$("#show").html('<span class="font-red">'+rs.content.rs.email+'/'+rs.content.rs.user+'</span>');
			}else{
				$("#show").html('<span class="font-red">'+rs.content+'</span>');
			}
		}
    })
</script>
<?php $this->output("foot","file"); ?>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","批量预报包裹"); ?><?php $this->output("head_member","file"); ?>
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
                                <i class="fa fa-bars"></i>批量导入包裹</div>
                        </div>
                        <div class="portlet-body">
                            <form method="post" id="saveorder">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td class="text-right">到货仓库：</td>
                                        <td>
                                            <select class="form-control" id="stock" name="stock">
                                                <?php $stock_id["num"] = 0;$stock=is_array($stock) ? $stock : array();$stock_id["total"] = count($stock);$stock_id["index"] = -1;foreach($stock AS $key=>$value){ $stock_id["num"]++;$stock_id["index"]++; ?>
                                                <option value="<?php echo $value['id'];?>"<?php if($user['stock_id'] == $value['id']){ ?> selected<?php } ?>><?php echo $value['title'];?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><?php echo $extlist['title'];?>：</td>
                                        <td><?php echo $extlist['html'];?></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button class="btn blue" type="submit">
                                            <i class="fa fa-edit"></i>
                                            批量导入
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet box grey">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-bars"></i>注意事项</div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-advance table-hover" id="tab">
                                <tbody>
                                <tr><td>1、请使用Excel创建一个.xls文件，格式如下（<a href="tpl/www/images/importpackage.xls" target="_blank" class="red" style="text-decoration: underline">点击下载模板</a>）。</td>
                                </tr>
                                <tr><td>2、Excel表格一次不能大于2M，超过2M的请分批导入。</td></tr>
                                <tr><td>3、客户单号为空，默认为系统自动生成单号。</td></tr>
                                <tr><td>4、请勿修改表格格式和重复导入Excel表，否则无法导入。</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var loading_action;
        $("#saveorder").submit(function(){
            //通过Ajax提交订单，客户端检查订单信息是否完整
            $(this).ajaxSubmit({
                'url':api_url('package','import'),
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
                        if(!rs.content) rs.content = '包裹批量导入失败';
                        $.dialog.alert(rs.content);
                        return false;
                    }
                    else
                    {
                        $.dialog.alert("包裹批量导入成功",function(){
                            $.dsy.go('<?php echo dsy_url(array('ctrl'=>'package','func'=>'index'));?>');
                        },'succeed');
                    }
                }
            });
            return false;
        });
    });
</script>
<?php $this->output("foot_member","file"); ?>
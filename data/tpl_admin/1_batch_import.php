<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head_open","file"); ?>
<form method="post" id="saveorder">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box grey">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" style="margin-top: 20px;">
                        <tbody>
                        <tr>
                            <td class="text-right"><?php echo $extlist['title'];?>：</td>
                            <td>
                                <?php echo $extlist['html'];?>
                            </td>
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
                </div>
            </div>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box grey">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-bars"></i>注意事项</div>
                <div class="tools">
                    <a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <tbody>
                    <tr><td>1、请使用Excel创建一个.xls文件，格式如下<span class="font-red">（<a href="tpl/www/images/batch_import.xls" class="red" style="text-decoration: underline">点击下载模板</a>）</span>。</td></tr>
                    <tr><td>2、Excel表格一次不能大于2M，超过2M的请分批导入。</td></tr>
                    <tr><td>3、根据运单编号匹配。</td></tr>
                    <tr><td>4、请勿修改表格格式和重复导入Excel表，否则无法导入。</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#saveorder").submit(function(){
            var dfile = $("#dfile").val();
            if(!dfile){
                $.dialog.alert("请上传Excel文件！");
                return false;
            }
            var obj = $.dialog.opener;
            //通过Ajax提交订单，客户端检查订单信息是否完整
            $(this).ajaxSubmit({
                'url':get_url('batch','importData'),
                'type':'post',
                'dataType':'json',
                'beforeSubmit':function(){
                    loading_action = $.dialog.tips('<img src="images/loading.gif" border="0" align="absmiddle" /> 正在保存数据，请稍候…').time(30).lock();
                },
                'success':function(rs){
                    if(loading_action){
                        loading_action.close();
                    }
                    if(rs.status != 'ok'){
                        if(!rs.content) rs.content = '导入失败';
                        alert(rs.content);
                        return false;
                    }
                    else
                    {
                        $.dialog.alert("成功导入",function(){
                            obj.$.dsy.reload();
                        },'succeed');
                    }
                }
            });
            return false;
        });
    });
</script>
<?php $this->output("foot_open","file"); ?>
<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<?php $this->output("nav","file"); ?>
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <?php $this->output("left","file"); ?>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->
            <!-- BEGIN PAGE BAR -->
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="<?php echo admin_url('email');?>">通知内容管理</a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li>
                        <span><?php if($id){ ?>编辑<?php } else { ?>添加<?php } ?></span>
                    </li>
                </ul>
                <div class="text-right" style="margin-top:6px;"><a href="<?php echo admin_url('email');?>" class="btn btn-circle green-haze btn-outline sbold uppercase btn-sm">返回列表</a></div>
            </div>
            <form method="post" action="<?php echo admin_url('email','setok');?>" onsubmit="return check_save()">
                <input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet box grey">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-bars"></i>通知内容设置</div>
                                <div class="tools">
                                    <a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td class="text-right"><?php echo P_Lang("标识：");?></td>
                                        <td><?php $identifier = $rs['identifier'] ? $rs['identifier'] : ($type == 'sms' ? 'sms_' : 'email_');?><input id="identifier" name="identifier" value="<?php echo $identifier;?>" type="text" style="width: 100%"/><span class="help-block"><?php echo P_Lang("该标识用于调用");?><?php if($type == 'sms'){ ?><?php echo P_Lang("，");?><?php echo P_Lang("短信模板请以：");?><span class="font-red">sms_</span> <?php echo P_Lang("开头");?><?php } else { ?><?php echo P_Lang("禁止使用");?> sms_ <?php echo P_Lang("开头");?><?php } ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <?php if($type == 'sms'){ ?>
                                        <td class="text-right"><?php echo P_Lang("模板标签：");?></td>
                                        <?php } else { ?>
                                        <td class="text-right"><?php echo P_Lang("邮件标题：");?></td>
                                        <?php } ?>

                                        <td><input id="title" name="title" type="text" value="<?php echo $rs['title'];?>" style="width: 100%"/><span class="help-block"><?php if($type == 'sms'){ ?><?php echo P_Lang("适用于阿里云短信等需要使用标签的地方，使用标签后，内容将可能是无效的");?><?php } else { ?><?php echo P_Lang("可以简单使用变量");?><?php } ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><?php echo P_Lang("备注：");?></td>
                                        <td><input name="note" id="note" value="<?php echo $rs['note'];?>" type="text" style="width: 100%"/>
                                        <span class="help-block"><?php echo P_Lang("对该模板内容的一些备注，建议使用您熟悉的语言");?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><?php echo P_Lang("内容：");?></td>
                                        <td><?php echo $edit_content;?>
                                            <span class="help-block"><?php echo P_Lang("支持模板变量");?></span></td>
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
            <?php if($type == 'sms'){ ?>
            <div class="note note-info" style="margin-top: 20px;">
                <h4 class="block fa fa-warning bold font-red"> 注意：</h4>
                <p><?php echo P_Lang("短信标识必须使用sms_开头，其他开头标识都归为邮件模板");?> </p>
                <p><?php echo P_Lang("部分短信接口不支持内容，只允许使用变量参数，这时内容是一行一条格式是：变量名:模板变量");?></p>
                <p>允许自定义的短信内容都有自身规范，建议使用阿里提供的短信 </p>
                <p>允许自定义的短信内容都有自身规范，建议使用阿里提供的短信 </p>
                <p>短信长度不要超过50字（请注意变量可能用到的长度） </p>
            </div>
            <?php } ?>
        </div>
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<script type="text/javascript">
    var type = "<?php echo $type;?>";
    function check_save()
    {
        var title = $("#title").val();
        if(!title){
            $.dialog.alert("主题不能为空");
            return false;
        }
        var identifier = $("#identifier").val();
        if(!identifier){
            $.dialog.alert("标识不能为空");
            return false;
        }
        if(type == "email"){
            if(identifier.substr(0,4) == 'sms_'){
                $.dialog.alert('不能使用sms_做标识');
                return false;
            }
            var content = UE.getEditor('content').getContentTxt();
            if(!content){
                $.dialog.alert("内容不能空");
                return false;
            }
        }else{
            if(identifier.substr(0,4) != 'sms_'){
                $.dialog.alert('必须使用sms_做标识前缀');
                return false;
            }
        }
        var url = get_url("email","check") + "&identifier="+$.str.encode(identifier)+"&type="+type;
        var id = $("#id").val();
        if(id && id != "undefined" && id != "0"){
            url += "&id="+id;
        }
        var rs = $.dsy.json(url);
        if(rs.status != 'ok'){
            $.dialog.alert(rs.content);
            return false;
        }
        $("#submit").attr("disabled",true);
        return true;
    }
</script>
<?php $this->output("foot","file"); ?>
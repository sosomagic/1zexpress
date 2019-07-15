<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->output("head","file"); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php echo admin_url('usergroup');?>" title="返回会员等级列表">会员等级管理</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span><?php if($id){ ?>编辑<?php } else { ?>添加<?php } ?>会员等级</span>
        </li>
    </ul>
</div>
<form method="post" action="<?php echo dsy_url(array('ctrl'=>'usergroup','func'=>'setok','id'=>$id));?>" onsubmit="return to_submit();">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover">
                        <tbody>
                        <tr>
                            <td class="text-right">会员等级名：</td>
                            <td><input id="title" name="title" type="text" value="<?php echo $rs['title'];?>" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">注册审核：</td>
                            <td>

                                <div class="md-radio-inline">
                                    <div class="md-radio">
                                        <input class="form-control" type="radio" id="register_status1" name="register_status" value="1" <?php if($rs['register_status'] == '1'){ ?> checked<?php } ?> class="md-radiobtn">
                                        <label for="register_status1">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> 免审核 </label>
                                    </div>
                                    <?php if($reglist){ ?>
                                    <div class="md-radio">
                                        <input type="radio" id="register_status2" name="register_status" value="email" <?php if($rs['register_status'] == 'email'){ ?> checked<?php } ?> class="md-radiobtn">
                                        <label for="register_status2">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> 邮箱验证注册 </label>
                                    </div>
                                    <div class="md-radio">
                                        <input type="radio" id="register_status3" name="register_status" value="code" <?php if($rs['register_status'] == 'code'){ ?> checked<?php } ?> class="md-radiobtn">
                                        <label for="register_status3">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> 邀请码验证 </label>
                                    </div>
                                    <?php } ?>
                                    <div class="md-radio">
                                        <input type="radio" id="register_status4" name="register_status" value="0" <?php if(!$rs['register_status']){ ?> checked<?php } ?> class="md-radiobtn">
                                        <label for="register_status4">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> 人工审核 </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">排序：</td>
                            <td><input id="taxis" name="taxis" value="<?php echo $rs['taxis'] ? $rs['taxis'] : 255;?>" class="form-control" />
                                <span class="help-block">设置排序，最大值不超过255，最小值为0，值越小越往前靠</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button class="btn blue" type="submit"> 提 交 </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>
<script type="text/javascript">
    function to_submit()
    {
        var title = $("#title").val();
        if(!title)
        {
            $.dialog.alert("会员等级名称不能为空");
            return false;
        }
    }
</script>
<?php $this->output("foot","file"); ?>
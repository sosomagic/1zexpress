<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","完善个人资料"); ?><?php $this->output("head_member","file"); ?>
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
                                <i class="fa fa-bars"></i>完善个人资料</div>
                            <div class="tools">
                                <a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <form method="post" id="userinfo_submit">
                            <table class="table table-striped table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <td class="text-right col-md-2">会员名：</td>
                                    <td><input class="form-control" type="text" value="<?php echo $rs['user'];?>" name="user" disabled/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">邮箱：</td>
                                    <td><input class="form-control" type="text" value="<?php echo $rs['email'];?>" name="email"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">手机：</td>
                                    <td><input class="form-control" type="text" value="<?php echo $rs['mobile'];?>" name="mobile"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">First Name：</td>
                                    <td><input class="form-control" type="text" name="first_name" placeholder="您的英文名或中文名全拼" value="<?php echo $rs['first_name'];?>" onkeyup="value=value.replace(/[^\w\.\s*/]/ig,'')"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">Last Name：</td>
                                    <td><input class="form-control" type="text" name="last_name" value="<?php echo $rs['ucode'];?>" readonly/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right">地址：</td>
                                    <td><input class="form-control" type="text" name="address" value="<?php echo $rs['address'];?>" placeholder="国外退货地址" onkeyup="value=value.replace(/[^\w\.\s*/]/ig,'')"/></td>
                                </tr>
                                <tr>
                                    <td class="text-right">邮编：</td>
                                    <td><input class="form-control" type="text" name="zipcode" value="<?php echo $rs['zipcode'];?>" placeholder="国外邮编"/></td>
                                </tr>
                                <tr>
                                    <td class="text-right">备用电话：</td>
                                    <td><input class="form-control" type="text" name="tel" value="<?php echo $rs['tel'];?>" placeholder="国外电话"/></td>
                                </tr>
                                <tr>
                                    <td class="text-right">微信：</td>
                                    <td><input class="form-control" type="text" name="weixin" value="<?php echo $rs['weixin'];?>"/></td>
                                </tr>
                                <tr>
                                    <td class="text-right">QQ：</td>
                                    <td><input class="form-control" type="text" name="qq" value="<?php echo $rs['qq'];?>"/></td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#userinfo_submit").submit(function(){
            $(this).ajaxSubmit({
                type:'post',
                url: api_url('usercp','info'),
                dataType:'json',
                success: function(rs){
                    if(rs.status == 'ok'){
                        $.dialog.alert("您的信息更新成功",function(){
                            //$.dsy.reload();
                            $.dsy.go('<?php echo dsy_url(array('ctrl'=>'usercp'));?>');
                        },'succeed');
                    }else{
                        $.dialog.alert(rs.content);
                        return false;
                    }
                }
            });
            return false;
        });
    });
    //更新会员组
    /*function update_group(gid)
    {
        $.dsy.go(get_url('usercp','','group_id='+gid));
    }*/
</script>
<?php $this->output("foot_member","file"); ?>
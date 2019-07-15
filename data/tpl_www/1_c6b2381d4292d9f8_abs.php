<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><?php $this->assign("title","个人设置"); ?><?php $this->output("head_member","file"); ?>
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
                                <i class="fa fa-bars"></i>账号绑定</div>
                            <div class="tools">
                                <a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <td class="font-bold text-center">登陆方式</td>
                                    <td class="font-bold">说明</td>
                                    <td class="font-bold"></td>
                                    <td class="font-bold text-center">操作</td>
                                </tr>
                                <?php if($qqlink){ ?>
                                <tr>
                                    <td class="text-center"><img src="plugins/loginext/images/qq.png" /></td>
                                    <td>绑定 QQ 号码，实现 QQ 一键登录，安全更快捷</td>
                                    <?php if($binder['qq']){ ?>
                                    <td class="text-center">已设置</td>
                                    <td class="text-center"><a href="javascript:unlock_action('qq','QQ');void(0);" class="btn btn-danger">解除</a></td>
                                    <?php } else { ?>
                                    <td class="font-red text-center"><span class="fa fa-close">未绑定</span></td>
                                    <td class="text-center"><a href="<?php echo $qqlink;?>" class="btn btn-info">现在绑定</a></td>
                                    <?php } ?>
                                </tr>
                                <?php } ?>
                                <?php if($wxlink){ ?>
                                <tr>
                                    <td class="text-center"><img src="plugins/loginext/images/weixin.png" /></td>
                                    <td>绑定微信号，使用微信扫一扫即可登录</td>
                                    <?php if($binder['weixin']){ ?>
                                    <td class="text-center">已设置</td>
                                    <td class="text-center"><a href="javascript:unlock_action('weixin','微信');void(0);" class="btn btn-danger">解除</a></td>
                                    <?php } else { ?>
                                    <td class="font-red text-center"><span class="fa fa-close">未绑定</span></td>
                                    <td class="text-center"><a href="<?php echo $wxlink;?>" class="btn btn-info">现在绑定</a></td>
                                    <?php } ?>
                                </tr>
                                <?php } ?>
                                <?php if($wblink){ ?>
                                <tr>
                                    <td align="center"><img src="plugins/loginext/images/weibo.png" /></td>
                                    <td>绑定新浪微博，登录更便捷</td>
                                    <?php if($binder['weibo']){ ?>
                                    <td>已设置</td>
                                    <td class="text-center"><a href="javascript:unlock_action('weibo','微博');void(0);" class="btn btn-danger">解除</a></td>
                                    <?php } else { ?>
                                    <td class="font-red text-center"><span class="fa fa-close">未绑定</span></td>
                                    <td class="text-center"><a href="<?php echo $wxlink;?>" class="btn btn-info">现在绑定</a></td>
                                    <?php } ?>
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
                                <i class="fa fa-bars"></i>自定义设置</div>
                            <div class="tools">
                                <a title="" data-original-title="" href="javascript:;" class="collapse"> </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <form method="post" id="setsubmit">
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td class="text-right">发货仓库：</td>
                                        <td>
                                            <select class="form-control" id="stock" name="stock">
                                                <option value="">请选择发货仓库</option>
                                                <?php $stock_id["num"] = 0;$stock=is_array($stock) ? $stock : array();$stock_id["total"] = count($stock);$stock_id["index"] = -1;foreach($stock AS $key=>$value){ $stock_id["num"]++;$stock_id["index"]++; ?>
                                                <option value="<?php echo $value['id'];?>"<?php if($rs['stock_id']==$value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right col-md-2">发货渠道：</td>
                                        <td>
                                            <select class="form-control" name="channel" id="channel">
                                                <option value="">请选择发货渠道</option>
                                                <?php $channel_list_id["num"] = 0;$channel_list=is_array($channel_list) ? $channel_list : array();$channel_list_id["total"] = count($channel_list);$channel_list_id["index"] = -1;foreach($channel_list AS $key=>$value){ $channel_list_id["num"]++;$channel_list_id["index"]++; ?>
                                                <option value="<?php echo $value['id'];?>"<?php if($rs['channel_id']==$value['id']){ ?> selected="selected"<?php } ?>><?php echo $value['title'];?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right col-md-2">短信通知：</td>
                                        <td>
                                            <input id="sms" name="sms" type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small" value="1"<?php if($rs['sms']){ ?> checked<?php } ?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right col-md-2">邮件通知：</td>
                                        <td>
                                            <input id="mail" name="mail" type="checkbox" class="make-switch"  data-on-text="<i class='fa fa-check'></i>" data-off-text="<i class='fa fa-times'></i>" data-size="small" value="1"<?php if($rs['mail']){ ?> checked<?php } ?>>
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function unlock_action(type,tip)
    {
        $.dialog.confirm('确定要解除'+tip+'绑定吗？',function(){
            var url = api_plugin_url('loginext','unbindaccount','type='+type);
            var rs = $.dsy.json(url);
            if(rs.status){
                $.dialog.alert('成功解除'+tip+'绑定',function(){
                    $.dsy.reload();
                },'succeed');
            }else{
                $.dialog.alert(rs.info);
            }
        })
    }
    $(document).ready(function(){
        $("#setsubmit").submit(function(){
            $(this).ajaxSubmit({
                type:'post',
                url: api_url('usercp','set'),
                dataType:'json',
                success: function(rs){
                    if(rs.status == 'ok'){
                        $.dialog.alert("自定义信息设置成功",function(){
                            $.dsy.reload();
                            //$.dsy.go('<?php echo dsy_url(array('ctrl'=>'usercp'));?>');
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
</script>
<?php $this->output("foot_member","file"); ?>

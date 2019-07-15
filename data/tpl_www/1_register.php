<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><html lang="zh-CN" xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>用户注册</title>
    <base href="<?php echo $sys['url'];?>" />
    <script type="text/javascript" src="<?php echo dsy_url(array('ctrl'=>'js'));?>" charset="utf-8"></script>
    <script type="text/javascript" src="tpl/www/js/global.js" charset="UTF-8"></script>
    <link href="css/artdialog.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap -->
    <link href="tpl/www/css/bootstrap.css" rel="stylesheet">
    <link href="tpl/www/css/style.css" rel="stylesheet">
    <script src="tpl/www/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        var open_vcode = '<?php echo $sys['is_vcode'] && function_exists("imagecreate") ? 1 : 0;?>';
        $(document).ready(function(){
            $("#register-form").submit(function(){
                if($('#is_ok').length){
                    if(!($('#is_ok').is(':checked'))){
                        $.dialog.alert('注册前请先同意本站协议');
                        return false;
                    }
                }
                $(this).ajaxSubmit({
                    type:'post',
                    url: api_url('register','save'),
                    dataType:'json',
                    success: function(rs){
                        if(rs.status == 'ok')
                        {
							var tips = rs.content;
							$.dialog.alert(tips,function(){
                            if(tips=="注册成功，等待管理员验证"){
                                $.dsy.go('<?php echo dsy_url(array('ctrl'=>'index'));?>');
                            }else{
                                $.dsy.go('<?php echo dsy_url(array('ctrl'=>'usercp'));?>');
                            }
                        },'succeed');
                        return false;
                        }
                        else
                        {
                            if(!rs.content) rs.content = '注册失败';
                            alert(rs.content);
                            if(open_vcode == '1')
                            {
                                $("#_chkcode").val('');
                                $("#update_vcode").dsy_vcode();
                            }
                            return false;
                        }
                    }
                });
                return false;
            });
        });
        function agreement(){
            var url = get_url('register','agreement');
            var title = '注册协议';
            $.dialog.open(url,{
                'title':title,
                'lock':true,
                'width':'70%',
                'height':'70%',
                'cancel':function(){
                    return true;
                }
            });
        }
    </script>
<?php echo $app->plugin_html_ap("dsyhead");?></head>
<body>
<div class="top">
    <div class="container clearfix">
        <div class="pull-left top-l">
            <div id="ddate" class="pull-left"></div><span id="ddate2"></span>
            <script src="tpl/www/js/time.js"></script>
        </div>
        <div class="pull-right">
            <div class="pull-left email"><?php echo $config['contact']['email'];?></div>
            <div class="pull-left phone"><?php echo $config['contact']['tel'];?></div>
        </div>
    </div>
</div>
<div class="header login-header">
    <div class="container clearfix">
        <div class="pull-left logo"><a href="<?php echo $sys['url'];?>" title="<?php echo $config['title'];?>"><img src="<?php echo $config['logo'];?>" alt="<?php echo $config['title'];?>"></a></div>
        <div class="pull-left welcome">感谢您注册<?php echo $config['title'];?></div>
        <div class="pull-right welcome">已有账号？<a href="<?php echo dsy_url(array('ctrl'=>'login'));?>">请登录</a></div>
    </div>
</div>
<div class="container">
    <div class="login-index register-index clearfix">
        <form method="post" id="register-form">
            <input type="hidden" name="group_id" value="2">
            <?php if($group_rs['register_status'] && $group_rs['register_status'] != 1){ ?>
            <div class="login-index-re">
                <div class="login-txt-icon"><span class="hidden-xs">验证串</span></div>
                <input type="text" class="form-control index-input" name="_code" readonly />
            </div>
            <?php } ?>
            <div class="login-index-re">
                <div class="login-txt-icon"><span class="hidden-xs">用户名</span></div>
                <input type="text" name="user"  class="form-control index-input" placeholder="请输入您的用户名" />
            </div>
            <div class="login-index-re">
                <div class="login-txt-icon login-txt-icon02"><span class="hidden-xs">设置密码</span></div>
                <input type="password" name="newpass" class="form-control index-input" placeholder="8-16位字符，数字、大小写字母、特殊字符" />
            </div>
            <div class="login-index-re">
                <div class="login-txt-icon login-txt-icon02"><span class="hidden-xs">确认密码</span></div>
                <input type="password" name="chkpass" class="form-control index-input" placeholder="请再次输入密码" />
            </div>
            <div class="login-index-re">
                <div class="login-txt-icon login-txt-phone"><span class="hidden-xs">手机号码</span></div>
                <input type="text" name="mobile" id="mobile" class="form-control index-input" placeholder="请输入您手机号码" />
            </div>
            <div class="login-index-re">
                <div class="login-txt-icon login-txt-email"><span class="hidden-xs">邮箱</span></div>
                <input type="text" name="email" class="form-control index-input" placeholder="请输入您的邮箱" />
            </div>
            <?php if($sys['is_vcode'] && function_exists("imagecreate")){ ?>
            <div class="login-index-re login-index-re-b">
                <div class="login-txt-icon login-txt-icon03"><span class="hidden-xs">验证码</span></div>
                <input type="text" id="_chkcode" name="_chkcode"  class="form-control index-input index-input-sm" placeholder="请输入验证码" />
                <img src="" border="0" align="absmiddle" id="update_vcode" title="点击切换验证码" class="btn btn-yzm" style="cursor:pointer;"/>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#update_vcode").dsy_vcode();
                    //更新点击时操作
                    $("#update_vcode").click(function(){
                        $(this).dsy_vcode();
                    });
                });
            </script>
            <?php } ?>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="is_ok" id="is_ok" checked> 我同意<a href="javascript:agreement();void(0);">《服务协议》</a>
                </label>
            </div>
            <button class="btn btn-login-t" />注 册</button>
        </form>
    </div>
</div>
<?php $this->output("foot","file"); ?>
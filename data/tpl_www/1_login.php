<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>会员登录</title>
    <base href="<?php echo $sys['url'];?>" />
    <script type="text/javascript" src="<?php echo dsy_url(array('ctrl'=>'js'));?>" charset="utf-8"></script>
    <script type="text/javascript" src="tpl/www/js/global.js" charset="UTF-8"></script>
    <link href="css/artdialog.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap -->
    <link href="tpl/www/css/bootstrap.css" rel="stylesheet">
    <link href="tpl/www/css/style.css" rel="stylesheet">
    <script src="tpl/www/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function check_input()
        {
            var user = $("input[name=user]").val();
            if(!user){
                $.dialog.alert('用户名不能为空');
                return false;
            }
            var pass = $("input[name=pass]").val();
            if(!pass){
                $.dialog.alert('密码不能为空');
                return false;
            }
            var chkcode = $("#_chkcode").val();
            if(!chkcode){
                $.dialog.alert('验证码不能为空','','error');
                return false;
            }
            return true;
        }
    </script>
<?php echo $app->plugin_html_ap("dsyhead");?></head>
<body class="login-back">
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
        <div class="pull-left welcome">欢迎登录</div>
    </div>
</div>
<div class="container">
    <div class="login-index clearfix">
        <div class="login-left pull-left">
            <div class="login-index-tt">用户登录</div>
            <form id="loginForm" method="post" onsubmit="return check_input()" action="<?php echo dsy_url(array('ctrl'=>'login','func'=>'ok'));?>">
                <input type="hidden" name="_back" value="<?php echo $_back;?>" />
                <div class="login-index-re">
                    <div class="login-txt-icon"><span class="hidden-xs">用户名</span></div>
                    <input type="text" name="user" class="form-control index-input" placeholder="请输入你的用户名" />
                </div>
                <div class="login-index-re">
                    <div class="login-txt-icon login-txt-icon02"><span class="hidden-xs">登录密码</span></div>
                    <input type="password"  name="pass" class="form-control index-input" placeholder="8-16位字符，数字、大小写字母、特殊字符" />
                </div>
                <?php if($sys['is_vcode'] && function_exists("imagecreate")){ ?>
                <div class="login-index-re login-index-re-b">
                    <div class="login-txt-icon login-txt-icon03"><span class="hidden-xs">验证码</span></div>
                    <input type="text" name="_chkcode" id="_chkcode" class="form-control index-input index-input-sm" placeholder="请输入验证码" />
                    <img src="" border="0" align="absmiddle" id="vcode" title="点击切换验证码" class="btn btn-yzm" style="cursor:pointer;" />
                </div>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $("#vcode").dsy_vcode();
                        $("#vcode").click(function(){
                            $(this).dsy_vcode();
                        });
                    });
                </script>
                <?php } ?>
                <button type="sumbit" class="btn btn-login-t" />登 录</button>
            </form>
            <div class="clearfix forget">
                <div class="pull-left"><a href="<?php echo dsy_url(array('ctrl'=>'register'));?>">注册账号</a></div>
                <div class="pull-right"><a href="<?php echo dsy_url(array('ctrl'=>'login','func'=>'getpass'));?>">忘记密码?</a></div>
            </div>
            <div class="kj-tt login-kj text-center"><span>第三方账号直接登录</span></div>
            <div class="login-lg text-center clearfix">
                <?php if($qqlink){ ?>
                <a href="<?php echo $qqlink;?>" title="QQ登录"><img src="tpl/www/images/qq-lg.png" /></a>
                <?php } ?>
                <?php if($wxlink){ ?>
                <a href="<?php echo $wxlink;?>" title="微信登录"><img src="tpl/www/images/wx-lg.png" /></a>
                <?php } ?>
                <?php if($wblink){ ?>
                <a href="<?php echo $wblink;?>" title="微博登录"><img src="tpl/www/images/wb-lg.png" /></a>
                <?php } ?>
            </div>
        </div>
        <div class="pull-right login-right">
            <div class="login-right-tt"><?php echo $config['title'];?></div>
            <?php echo $config['r_info']['content'];?>
            <p><img src="tpl/www/images/map.png"></p>
        </div>
    </div>
</div>
<?php $this->output("foot","file"); ?>
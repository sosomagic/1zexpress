<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><html lang="zh-CN" xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>取回密码</title>
    <base href="<?php echo $sys['url'];?>" />
    <script type="text/javascript" src="<?php echo dsy_url(array('ctrl'=>'js'));?>" charset="utf-8"></script>
    <script type="text/javascript" src="tpl/www/js/global.js" charset="UTF-8"></script>
    <link href="css/artdialog.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap -->
    <link href="tpl/www/css/bootstrap.css" rel="stylesheet">
    <link href="tpl/www/css/style.css" rel="stylesheet">
    <script src="tpl/www/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#getpass-form').submit(function(){
                $(this).ajaxSubmit({
                    url:api_url('login','getpass'),
                    type:'post',
                    dataType:'json',
                    success:function(rs){
                        if(rs.status == 'ok')
                        {
                            alert("请登录您的邮箱取得相关信息，以便进行下一步操作");
                            $.dsy.go(webroot);
                        }
                        else
                        {
                            if(!rs.content) rs.content = '获取失败，请联系管理员。';
                            alert(rs.content);
                            return false;
                        }
                    }
                });
                return false;
            });
        });
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
        <div class="pull-left welcome">取回密码</div>
        <div class="pull-right welcome">已有账号？<a href="<?php echo dsy_url(array('ctrl'=>'login'));?>">请登录</a></div>
    </div>
</div>
<div class="container">
    <div class="login-index register-index clearfix">
        <form method="post" id="getpass-form">
            <div class="login-index-re">
                <div class="login-txt-icon login-txt-email"><span class="hidden-xs">邮箱</span></div>
                <input type="text" name="email" class="form-control index-input" placeholder="请输入您注册时候填写的邮箱" />
            </div>
            <?php if($sys['is_vcode'] && function_exists("imagecreate")){ ?>
            <div class="login-index-re login-index-re-b">
                <div class="login-txt-icon login-txt-icon03"><span class="hidden-xs">验证码</span></div>
                <input type="text" id="_chkcode" name="_chkcode"  class="form-control index-input index-input-sm" placeholder="请输入验证码" />
                <img src="" border="0" align="absmiddle" id="vcode" title="点击切换验证码" class="btn btn-yzm" style="cursor:pointer;"/>
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
            <button class="btn btn-login-t" />取回密码</button>
        </form>
    </div>
</div>
<?php $this->output("foot","file"); ?>
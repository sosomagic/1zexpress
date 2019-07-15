<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <title>后台登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- CSS -->
    <link rel="stylesheet" href="framework/view/login/css/supersized.css">
    <link rel="stylesheet" href="framework/view/login/css/login.css">
    <link href="framework/view/login/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/artdialog.css" rel="stylesheet" type="text/css" />
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="framework/view/login/js/html5.js"></script>
    <![endif]-->
    <script type="text/javascript" src="<?php echo dsy_url(array('ctrl'=>'js','ext'=>'admin.login.js'));?>"></script>
<?php echo $app->plugin_html_ap("dsyhead");?></head>
<body>
<div class="page-container">
    <div class="main_box">
        <div class="login_box">
            <div class="login_logo">
                <?php if($config['adm_logo180']){ ?>
                <a href="<?php echo $config['domain'];?>"><img src="<?php echo $config['adm_logo180'];?>" alt="<?php echo $config['title'];?>" /></a>
                <?php } ?>
            </div>
            <div class="login_form">
                <form id="adminlogin" method="post" onsubmit="return admlogin()">
                    <div class="form-group">
                        <label for="user" class="t">用户名：</label>
                        <input name="user" id="user" type="text" value="" class="form-control x319 in" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="pass" class="t">密　码：</label>
                        <input name="pass" id="pass" value="" type="password" class="password form-control x319 in">
                    </div>
                    <?php if($vcode){ ?>
                    <div class="form-group">
                        <label for="code_id" class="t">验证码：</label>
                        <input id="code_id" name="code_id" type="text" class="form-control x164 in">
                        <img alt="点击更换" title="点击更换" src="images/blank.gif" border="0" align="absmiddle" style="cursor:pointer;" onclick="login_code('<?php echo $sys['app_id'];?>')" id="src_code" class="m"><script type="text/javascript">$(document).ready(function(){login_code("<?php echo $sys['app_id'];?>");});</script>
                    </div>
                    <?php } ?>
                    <div class="form-group space">
                        <label class="t"></label>　　　
                        <button type="submit" class="btn btn-primary btn-lg">&nbsp;登&nbsp;录&nbsp </button>
                        <input type="reset" value="&nbsp;重&nbsp;置&nbsp;" class="btn btn-default btn-lg">
                    </div>
                </form>
            </div>
        </div>
        <div class="bottom">© DSYEX多仓版</div>
    </div>
</div>
<!-- Javascript -->
<script src="framework/view/login/js/supersized.3.2.7.min.js"></script>
<script src="framework/view/login/js/supersized-init.js"></script>
<script src="framework/view/login/js/scripts.js"></script>
</div>
<?php echo $app->plugin_html_ap("dsybody");?></body>
</html>
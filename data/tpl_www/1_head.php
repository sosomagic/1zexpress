<?php if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");} ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="author" content="dsaiyin" />
    <title><?php if($title){ ?><?php echo $title;?> - <?php } ?><?php echo $config['title'];?><?php if($seo['title']){ ?> - <?php echo $seo['title'];?><?php } ?></title>
    <meta name="keywords" content="<?php echo $seo['keywords'];?>" />
    <meta name="description" content="<?php echo $seo['description'];?>" />
    <base href="<?php echo $sys['url'];?>" />
    <script type="text/javascript" src="<?php echo dsy_url(array('ctrl'=>'js'));?>" charset="utf-8"></script>
    <script type="text/javascript" src="tpl/www/js/global.js" charset="UTF-8"></script>
    <link href="css/artdialog.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap -->
    <link href="tpl/www/css/bootstrap.css" rel="stylesheet">
    <link href="tpl/www/css/style.css" rel="stylesheet">
    <script src="tpl/www/js/bootstrap.min.js"></script>
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
<div class="header">
    <div class="container clearfix">
        <div class="pull-left logo"><a href="<?php echo $sys['url'];?>" title="<?php echo $config['title'];?>"><img src="<?php echo $config['logo'];?>" alt="<?php echo $config['title'];?>"></a></div>
        <?php $list = dsy('menu');?>
        <?php $mindex["num"] = 0;$list['rslist']=is_array($list['rslist']) ? $list['rslist'] : array();$mindex["total"] = count($list['rslist']);$mindex["index"] = -1;foreach($list['rslist'] AS $key=>$value){ $mindex["num"]++;$mindex["index"]++; ?>
        <ul class="pull-left clearfix nav-list">
            <li<?php if($highlight == $mindex['num'] || $menutitle == $value['title']){ ?> class="active"<?php } ?>>
            <a href="<?php echo $value['url'];?>"><?php echo $value['title'];?></a>
            <?php if($value['sonlist']){ ?>
            <span class="nav-down-c"><i class="glyphicon glyphicon-plus"></i></span>
            <ul class="nav-down">
                <?php $value_sonlist_id["num"] = 0;$value['sonlist']=is_array($value['sonlist']) ? $value['sonlist'] : array();$value_sonlist_id["total"] = count($value['sonlist']);$value_sonlist_id["index"] = -1;foreach($value['sonlist'] AS $k=>$v){ $value_sonlist_id["num"]++;$value_sonlist_id["index"]++; ?>
                <li><a href="<?php echo $v['url'];?>"><?php echo $v['title'];?></a></li>
                <?php } ?>
            </ul>
            <?php } ?>
            </li>
        </ul>
        <?php } ?>
        <div class="pull-right header-right">
            <?php if($session['user_id']){ ?>
            <a href="<?php echo dsy_url(array('ctrl'=>'usercp'));?>" class="user-icon">会员中心<span class="caret"></span></a>
            <a href="javascript:$.user.logout('<?php echo $session['user_name'];?>');void(0)" class="user-out">退出</a>
            <?php } else { ?>
            <a href="<?php echo dsy_url(array('ctrl'=>'register'));?>" class="register-link">注册</a>
            <a href="<?php echo dsy_url(array('ctrl'=>'login'));?>" class="register-login">登录</a>
            <?php } ?>
        </div>
        <button type="button" class="navbar-toggle collapsed">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
</div>
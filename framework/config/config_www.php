<?php
/***********************************************************
	Filename: config/config_admin.php
	Note	: 后台控制器
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2012-10-17 15:36
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}

//前台自动加载的JS，此处的JS对应的CSS，HTML及图片路径是相对于网站根目录
$config["autoload_js"] .= ",global.www.js,jquery.superslide.js,jquery.artdialog.js";

$config["autoload_func"] = "";


$config["is_vcode"] = true;

$config["gzip"] = true;//启用压缩


<?php
/***********************************************************
	Filename: config.php
	Note	: 配置文件，此配置应用于全局，修改完后建议设为只读
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ850272422>
	Update  : 2016年5月29日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
// 连接数据库
$config["db"]["file"] = "mysqli";
$config["db"]["host"] = "127.0.0.1";
$config["db"]["port"] = "3306";
$config["db"]["user"] = "www_1zexpress_co";
$config["db"]["pass"] = "GPNpxs5FhpYnxKhB";
$config["db"]["data"] = "www_1zexpress_co";
$config["db"]["prefix"] = "dsy_";
$config['db']['socket'] = '';
$config['db']['debug'] = false;
$config['develop'] = false; //开发模式，正常运行的网站请设为false，可防止CRSF注入
$config['debug'] = false;
?>
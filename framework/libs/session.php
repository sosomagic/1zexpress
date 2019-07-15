<?php
/***********************************************************
	Filename: libs/session.php
	Note	: SESSION通用版
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2013年8月17日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class session_lib
{
	var $sessid;
	var $timeout = 3600;
	function __construct()
	{
		//
	}

	function sessid($sid="")
	{
		if($sid) $this->sessid = $sid;
		return $this->sessid;
	}

	function config($config)
	{
		$this->config = $config;
		$this->timeout = $config["session_timeout"];
	}
}
?>
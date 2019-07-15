<?php
/*****************************************************************************************
	文件： form/productattr_form.php
	备注： 产品属性编辑器
    Version : 2.0
    Web		: www.dsaiyin.com
    Author  : dsaiyin <QQ:850272422>
    Update  : 2016年10月11日
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class productattr_form extends _init_auto
{
	public function __construct()
	{
		parent::__construct();
	}

	public function dsy_config()
	{
		$this->view($this->dir_dsy.'form/html/productattr_admin.html','abs-file');
	}

	public function dsy_format($rs,$appid="admin")
	{
		//
	}

	public function dsy_get($rs,$appid="admin")
	{
		//
	}

	public function dsy_show($rs,$appid="admin")
	{
		//
	}
}
?>
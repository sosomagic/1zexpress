<?php
/*****************************************************************************************
	文件： form/code_editor_form.php
	备注： 代码编辑框
    Version : 2.0
    Web		: www.dsaiyin.com
    Author  : dsaiyin <QQ:850272422>
    Update  : 2016年10月11日
*****************************************************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class code_editor_form extends _init_auto
{
	public function __construct()
	{
		parent::__construct();
	}

	public function dsy_config()
	{
		$this->view($this->dir_dsy.'form/html/code_admin.html',"abs-file");
	}

	public function dsy_format($rs,$appid="admin")
	{
		$this->addjs('js/codemirror/codemirror.js');
		$this->addcss('js/codemirror/codemirror.css');
		$this->assign("_rs",$rs);
		return $this->fetch($this->dir_dsy.'form/html/code_admin_tpl.html','abs-file');
	}

	public function dsy_get($rs,$appid="admin")
	{
		return $this->get($rs['identifier'],'html_js');
	}

	public function dsy_show($rs,$appid="admin")
	{
		return $rs['content'];
	}
}
?>
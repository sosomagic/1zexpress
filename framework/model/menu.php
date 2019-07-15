<?php
/***********************************************************
	Filename: model/menu.php
	Note	: 导航菜单管理
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2013-02-08 16:59
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class menu_model_base extends dsy_model
{
	var $site_id = 0;
	function __construct()
	{
		parent::model();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this);
	}

	function get_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."menu WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}
}
?>
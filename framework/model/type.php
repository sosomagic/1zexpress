<?php
/***********************************************************
	Filename: model/type.php
	Note	: 类型
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2012-12-11 10:01
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class type_model_base extends dsy_model
{
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
		$sql = "SELECT * FROM ".$this->db->prefix."type WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}

	function get_id($sign)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."type WHERE sign='".$sign."'";
		return $this->db->get_one($sql);
	}
}
?>
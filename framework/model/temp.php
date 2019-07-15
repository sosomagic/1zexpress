<?php
/***********************************************************
	Filename: model/temp.php
	Note	: 临时存储器（适用于自动数据保存）
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2012-12-10 00:04
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class temp_model_base extends dsy_model
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

	function save($data,$id=0)
	{
		return false;
	}

	function get_one($id)
	{
		return false;
	}

	function chk($tbl,$admin_id)
	{
		return false;
	}

	function clean($tbl,$admin_id)
	{
		return false;
	}
}
?>
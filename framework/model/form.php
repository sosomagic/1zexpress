<?php
/***********************************************************
	Filename: model/form.php
	Note	: 表单选择器
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2013-03-12 17:34
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class form_model_base extends dsy_model
{
	public $info = "";
	function __construct()
	{
		parent::model();
		$this->info = $this->lib('xml')->read($this->dir_dsy.'system.xml');
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this);
	}

	function form_all()
	{
		if($this->info['form'])
		{
			return $this->info['form'];
		}
		return false;
	}

	function format_all()
	{
		if($this->info['format'])
		{
			return $this->info['format'];
		}
		return false;
	}

	//字段类型
	function field_all()
	{
		if($this->info['field'])
		{
			return $this->info['field'];
		}
		return false;
	}

	//读取表单下的子项目信息
	public function project_sublist($pid)
	{
		$sql = "SELECT id as val,title FROM ".$this->db->prefix."project WHERE parent_id=".intval($pid)." AND status=1 ";
		$sql.= "ORDER BY taxis ASC,id DESC";
		return $this->db->get_all($sql);
	}
}
?>
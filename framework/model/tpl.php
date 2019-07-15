<?php
/***********************************************************
	Filename: model/tpl.php
	Note	: 模板管理，涉及到的数据表：dsy_tpl
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:850272422>
	Update  : 2012-10-17 15:15
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class tpl_model_base extends dsy_model
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

	# 获取模板信息
	function get_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."tpl WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}

	# 取得全部风格列表
	function get_all()
	{
		$sql = "SELECT * FROM ".$this->db->prefix."tpl ORDER BY folder ASC";
		return $this->db->get_all($sql);
	}

	//存储或添加风格信息
	function save($data,$id=0)
	{
		if(!$data || !is_array($data)) return false;
		if($id)
		{
			return $this->db->update_array($data,"tpl",array("id"=>$id));
		}
		else
		{
			return $this->db->insert_array($data,"tpl");
		}
	}

	function delete($id)
	{
		if(!$id) return false;
		$sql = "DELETE FROM ".$this->db->prefix."tpl WHERE id='".$id."'";
		return $this->db->query($sql);
	}
}
?>
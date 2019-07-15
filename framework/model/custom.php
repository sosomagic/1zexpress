<?php
/***********************************************************
	Filename: model/custom.php
	Note	: 自定义服务理器
	Version : 2.0
	Update  : 16:32 2016-1-9
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class custom_model_base extends dsy_model
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

	function get_list($pri='')
	{
		$sql = "SELECT * FROM ".$this->db->prefix."custom ORDER BY taxis ASC,id DESC";
		return $this->db->get_all($sql,$pri);
	}
    function get_customList($pri='')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."custom where status=1 ORDER BY taxis ASC,id DESC";
        return $this->db->get_all($sql,$pri);
    }
	function get_one($id,$field_id='id')
	{
		$sql = "SELECT * FROM ".$this->db->prefix."custom WHERE ".$field_id."='".$id."'";
		return $this->db->get_one($sql);
	}
    function get_custom($cate_id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."custom where status=1 and cate_id=".$cate_id." ORDER BY taxis ASC,id DESC";
        return $this->db->get_all($sql);
    }

	//存储信息
	function save($data,$id="")
	{
		if(!$data || !is_array($data)) return false;
		if($id)
		{
			return $this->db->update_array($data,"custom",array("id"=>$id));
		}
		else
		{
			return $this->db->insert_array($data,"custom","replace");
		}
	}

	function update_status($id,$status=0)
	{
		$sql = "UPDATE ".$this->db->prefix."custom SET status='".$status."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}

	function update_sort($id,$taxis=255)
	{
		$sql = "UPDATE ".$this->db->prefix."custom SET taxis='".$taxis."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}


	//删除操作
	function del($id)
	{
		$sql = "DELETE FROM ".$this->db->prefix."custom WHERE id='".$id."'";
		return $this->db->query($sql);
	}
}
?>
<?php
/***********************************************************
	Filename: model/delivery.php
	Note	: 上门取件
	Version : 2.0
	Update  : 23:12 2016-8-4
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class delivery_model_base extends dsy_model
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
    function get_list($condition='',$offset=0,$psize=20)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."delivery ";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        $offset = intval($offset);
        $psize = intval($psize);
        $sql .= " ORDER BY addtime DESC,id DESC LIMIT ".$offset.",".$psize;
        return $this->db->get_all($sql);
    }
	function get_one($id,$field_id='id')
	{
		$sql = "SELECT * FROM ".$this->db->prefix."delivery WHERE ".$field_id."='".$id."'";
		return $this->db->get_one($sql);
	}

	//存储信息
	function save($data,$id="")
	{
		if(!$data || !is_array($data)) return false;
		if($id)
		{
			return $this->db->update_array($data,"delivery",array("id"=>$id));
		}
		else
		{
			return $this->db->insert_array($data,"delivery","replace");
		}
	}

	function update_status($id,$status=0)
	{
		$sql = "UPDATE ".$this->db->prefix."delivery SET status='".$status."' WHERE id='".$id."'";
		return $this->db->query($sql);
	}

	//删除操作
	function del($id)
	{
		$sql = "DELETE FROM ".$this->db->prefix."delivery WHERE id='".$id."'";
		return $this->db->query($sql);
	}
    function get_count($condition="")
    {
        $sql = "SELECT count(id) FROM ".$this->db->prefix."delivery ";
        if($condition)
        {
            $sql .= " WHERE ".$condition;
        }
        return $this->db->count($sql);
    }
}
?>
<?php
/**
 * Author: dsaiyin
 * Web: www.dsaiyin.com
 * QQ: 17189095
 * Date: 16-8-11
 * Time: 下午5:15
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class claim_model_base extends dsy_model
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
        $sql = "SELECT * FROM ".$this->db->prefix."claim ";
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
        $sql = "SELECT * FROM ".$this->db->prefix."claim WHERE ".$field_id."='".$id."'";
        return $this->db->get_one($sql);
    }

    //存储信息
    function save($data,$id="")
    {
        if(!$data || !is_array($data)) return false;
        if($id)
        {
            return $this->db->update_array($data,"claim",array("id"=>$id));
        }
        else
        {
            return $this->db->insert_array($data,"claim","replace");
        }
    }

    function update_status($id,$status=0)
    {
        $sql = "UPDATE ".$this->db->prefix."claim SET status='".$status."' WHERE id='".$id."'";
        return $this->db->query($sql);
    }

    //删除操作
    function del($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."claim WHERE id='".$id."'";
        return $this->db->query($sql);
    }
	 public function get_count($condition="")
    {
        $sql = "SELECT count(id) FROM ".$this->db->prefix."claim";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        return $this->db->count($sql);
    }
}
?>
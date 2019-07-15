<?php
/**
 * Created by PhpStorm.
 * User: lark
 * Date: 17-8-16
 * Time: 下午11:22
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class purchase_model_base extends dsy_model
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
        $sql = "SELECT * FROM ".$this->db->prefix."purchase ";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        $offset = intval($offset);
        $psize = intval($psize);
        $sql .= " ORDER BY addtime DESC,id DESC LIMIT ".$offset.",".$psize;
        return $this->db->get_all($sql);
    }
    public function get_count($condition="")
    {
        $sql = "SELECT count(o.id) FROM ".$this->db->prefix."purchase o ";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        return $this->db->count($sql);
    }
    function save($data,$id=0)
    {
        if(!$data || !is_array($data)) return false;
        if($id)
        {
            return $this->db->update_array($data,"purchase",array("id"=>$id));
        }
        else
        {
            return $this->db->insert_array($data,"purchase");
        }
    }
    function get_one($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."purchase WHERE id='".$id."'";
        return $this->db->get_one($sql);
    }
    function del($id)
    {
        if(!$id) return false;
		$sql = "DELETE FROM ".$this->db->prefix."purchase WHERE id=".intval($id);
		$this->db->query($sql);
        return true;
    }

}
?>
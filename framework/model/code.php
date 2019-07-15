<?php
/**
 * Author: dsaiyin
 * Web: www.dsaiyin.com
 * QQ: 17189095
 * Date: 16-7-22
 * Time: 下午8:28
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class code_model_base extends dsy_model
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

    function get_list($condition='',$offset=0,$psize=50)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."code";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        $offset = intval($offset);
        $psize = intval($psize);
        $sql .= " ORDER BY id DESC LIMIT ".$offset.",".$psize;
        return $this->db->get_all($sql);
    }
    function get_code($condition='')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."code";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        $sql .= " ORDER BY id DESC";
        return $this->db->get_one($sql);
    }
    function get_all()
    {
        $sql = "SELECT * FROM ".$this->db->prefix."code order by id desc";
        return $this->db->get_all($sql);
    }
    function get_one($id,$field_id='id')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."code WHERE ".$field_id."='".$id."'";
        return $this->db->get_one($sql);
    }
    function get_count($condition="")
    {
        $sql = "SELECT count(id) FROM ".$this->db->prefix."code";
        if($condition)
        {
            $sql .= " WHERE ".$condition;
        }
        return $this->db->count($sql);
    }
    //存储信息
    function save($data,$id="")
    {
        if(!$data || !is_array($data)) return false;
        if($id)
        {
            return $this->db->update_array($data,"code",array("id"=>$id));
        }
        else
        {
            return $this->db->insert_array($data,"code","replace");
        }
    }

    //删除操作
    function del($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."code WHERE id='".$id."'";
        return $this->db->query($sql);
    }
}
?>
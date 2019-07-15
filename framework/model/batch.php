<?php
/**
 * Author: dsaiyin
 * Web: www.dsaiyin.com
 * QQ: 17189095
 * Date: 16-7-17
 * Time: 下午1:47
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class batch_model_base extends dsy_model
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
        $sql = "SELECT * FROM ".$this->db->prefix."batch";
        if($condition){
            $sql .= " WHERE ".$condition;
        }
        $offset = intval($offset);
        $psize = intval($psize);
        $sql .= " ORDER BY id DESC LIMIT ".$offset.",".$psize;
        return $this->db->get_all($sql);
    }
    function get_all()
    {
        $sql = "SELECT * FROM ".$this->db->prefix."batch order by id desc";
        return $this->db->get_all($sql);
    }
    function get_chk_all()
    {
        $sql = "SELECT * FROM ".$this->db->prefix."batch where status=0 order by id desc";
        return $this->db->get_all($sql);
    }

    function get_one($id,$field_id='id')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."batch WHERE ".$field_id."='".$id."'";
        return $this->db->get_one($sql);
    }
    function get_count($condition="")
    {
        $sql = "SELECT count(id) FROM ".$this->db->prefix."batch";
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
            return $this->db->update_array($data,"batch",array("id"=>$id));
        }
        else
        {
            return $this->db->insert_array($data,"batch","replace");
        }
    }

    //删除操作
    function del($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."batch WHERE id='".$id."'";
        return $this->db->query($sql);
    }
    function cate($id){
        $sql = "UPDATE ".$this->db->prefix."order SET cate_id=0 WHERE cate_id=".intval($id);
        $this->db->query($sql);
        return true;
    }
}
?>
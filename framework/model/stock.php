<?php
/**
 * Author: dsaiyin
 * Web: www.dsaiyin.com
 * QQ: 17189095
 * Date: 16-5-20
 * Time: 下午1:32
 */
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class stock_model_base extends dsy_model
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
        $sql = "SELECT * FROM ".$this->db->prefix."stock ORDER BY taxis ASC,id DESC";
        return $this->db->get_all($sql,$pri);
    }
    function get_stockList($pri='')  //会员中心仓库列表
    {
        $sql = "SELECT * FROM ".$this->db->prefix."stock where status =1 ORDER BY taxis ASC,id DESC";
        return $this->db->get_all($sql,$pri);
    }
    function get_stock($ids)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."stock where id in(".$ids.") ORDER BY taxis ASC,id DESC";
        return $this->db->get_all($sql);
    }
    function get_one($id,$field_id='id')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."stock WHERE ".$field_id."='".$id."'";
        return $this->db->get_one($sql);
    }

    //存储信息
    function save($data,$id="")
    {
        if(!$data || !is_array($data)) return false;
        if($id)
        {
            return $this->db->update_array($data,"stock",array("id"=>$id));
        }
        else
        {
            return $this->db->insert_array($data,"stock","replace");
        }
    }

    function update_status($id,$status=0)
    {
        $sql = "UPDATE ".$this->db->prefix."stock SET status='".$status."' WHERE id='".$id."'";
        return $this->db->query($sql);
    }

    function update_sort($id,$taxis=255)
    {
        $sql = "UPDATE ".$this->db->prefix."stock SET taxis='".$taxis."' WHERE id='".$id."'";
        return $this->db->query($sql);
    }
    //删除操作
    function del($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."stock WHERE id='".$id."'";
        return $this->db->query($sql);
    }
	//后台管理员管理仓库权限
    function stock_from_admin($ids)  //会员中心仓库列表
    {
        $sql = "SELECT * FROM ".$this->db->prefix."stock where status =1 and id in(".$ids.") ORDER BY taxis ASC,id DESC";
        return $this->db->get_all($sql);
    }
}
?>
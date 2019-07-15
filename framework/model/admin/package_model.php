<?php
/***********************************************************
	Filename: /model/www/package_model.php
	Note	: 包裹信息及管理
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:17189095>
	Update  : 2016年5月17日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class package_model extends package_model_base
{
	function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this);
	}
    public function get_list($condition='',$offset=0,$psize=30)
    {
        $sql= "SELECT o.*,u.user,u.ucode";
        $sql.= " FROM ".$this->db->prefix."package o ";
        $sql.= "LEFT JOIN ".$this->db->prefix."user u ON(o.user_id=u.id) ";
        if($condition){
            $sql.= "WHERE ".$condition." ";
        }
        $sql.= "ORDER BY o.id DESC LIMIT ".$offset.",".$psize;
       // echo $sql;
       // exit;
        return $this->db->get_all($sql);
    }
	function get_count($condition="")
	{
		$sql = "SELECT count(id) FROM ".$this->db->prefix."package o";
		if($condition)
		{
			$sql .= " WHERE ".$condition;
		}
		return $this->db->count($sql);
	}

	function save($data,$id=0)
	{
		if(!$data || !is_array($data)) return false;
		if($id)
		{
			return $this->db->update_array($data,"package",array("id"=>$id));
		}
		else
		{
			return $this->db->insert_array($data,"package");
		}
	}
    //取得包裹产品信息
    /*function pro_list($ids)
    {
        if(!$ids){
            return false;
        }
        $sql = "SELECT * FROM ".$this->db->prefix."order_product WHERE package_id in (".$ids.") order by id asc";
        $rslist = $this->db->get_all($sql);
        if(!$rslist){
            return false;
        }
        return $rslist;
    }*/
    /*function get_one($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."package WHERE id='".$id."'";
        return $this->db->get_one($sql);
    }*/
    public function update_status($id,$status=0)
    {
        $sql = "UPDATE ".$this->db->prefix."package SET status='".$status."' WHERE id='".$id."'";
        return $this->db->query($sql);
    }
    //删除订单操作
    /*function delete($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."package WHERE id=".intval($id);
        $this->db->query($sql);
        return true;
    }*/
    //后台包裹状态
    /*public function status()
    {
        return array('未入库包裹','已入库包裹','等待付款','问题包裹','已创建包裹');
    }*/
    //后台运单详情取得包裹列表
    function get_package($condition='')
    {
        $sql = "SELECT * FROM ".$this->db->prefix."package ";
        if($condition)
        {
            $sql .= " WHERE ".$condition;
        }
        $sql .= " ORDER BY addtime DESC,id DESC";
        $rslist = $this->db->get_all($sql);
        if(!$rslist){
            return false;
        }
        return $rslist;
    }
    /*function get_pro($id)
    {
        if(!$id){
            return false;
        }
        $sql = "SELECT * FROM ".$this->db->prefix."order_product WHERE package_id = ".$id." order by id asc";
        return $this->db->get_all($sql);
    }*/
	function get_product($id)
    {
        if(!$id){
            return false;
        }
        $sql = "SELECT * FROM ".$this->db->prefix."package_product WHERE id = ".$id;
        return $this->db->get_one($sql);
    }
}

?>
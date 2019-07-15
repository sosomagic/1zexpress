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

	//取得包裹列表
	function get_list($condition='',$offset=0,$psize=20)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."package ";
		if($condition)
		{
			$sql .= " WHERE ".$condition;
		}
		$offset = intval($offset);
		$psize = intval($psize);
		$sql .= " ORDER BY addtime DESC,id DESC LIMIT ".$offset.",".$psize;
		$rslist = $this->db->get_all($sql);
		if(!$rslist){
			return false;
		}
		$status_list = $this->status_list();
		$order_idlist = array();
		foreach($rslist as $key=>$value){
			$value['status_info'] = ($status_list && $status_list[$value['status']]) ? $status_list[$value['status']] : $value['status'];
			$rslist[$key] = $value;
			$order_idlist[] = $value['id'];
		}
		$order_ids = implode(",",$order_idlist);
		$sql = "SELECT SUM(qty) as qty,order_id FROM ".$this->db->prefix."order_product WHERE order_id IN(".$order_ids.") GROUP BY order_id";
		$tmplist = $this->db->get_all($sql,'order_id');
		if($tmplist){
			foreach($rslist as $key=>$value){
				$value['qty'] = $tmplist[$value['id']] ? $tmplist[$value['id']]['qty'] : 0;
				$rslist[$key] = $value;
			}
		}
		return $rslist;
	}

	function get_count($condition="")
	{
		$sql = "SELECT count(id) FROM ".$this->db->prefix."package ";
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
    /*//取得包裹产品信息
    function pro_list($ids)
    {
        if(!$ids){
            return false;
        }
        $sql = "SELECT * FROM ".$this->db->prefix."order_product WHERE package_id in (".$ids.") order by id asc";
        $rslist = $this->db->get_all($sql);
        if(!$rslist){
            $this->json(P_Lang("包裹没有添加产品"));
            return false;
        }

        return $rslist;
    }*/
    /*function get_pro($id)
    {
        if(!$id){
            return false;
        }
        $sql = "SELECT * FROM ".$this->db->prefix."order_product WHERE package_id = ".$id." order by id asc";
        return $this->db->get_all($sql);
    }*/
    function get_one($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."package WHERE id='".$id."'";
        return $this->db->get_one($sql);
    }
    //创建包裹运单，查询包裹
    function get_package($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."package WHERE id in(".$id.") ORDER BY id DESC";
        return $this->db->get_all($sql);
    }
}

?>
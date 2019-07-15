<?php
/***********************************************************
	Filename: /model/www/order_model.php
	Note	: 订单信息及管理
	Version : 2.0
	Web		: www.dsaiyin.com
	Author  : dsaiyin <QQ:17189095>
	Update  : 2013年12月8日
***********************************************************/
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class order_model extends order_model_base
{
	function __construct()
	{
		parent::__construct();
	}
	//取得订单列表
	function get_list($condition='',$offset=0,$psize=20)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."order";
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
		return $rslist;
	}

	function get_count($condition="")
	{
		$sql = "SELECT count(id) FROM ".$this->db->prefix."order ";
		if($condition)
		{
			$sql .= " WHERE ".$condition;
		}
		return $this->db->count($sql);
	}
    function get_max($condition="")
    {
        $sql = "SELECT MAX(right(sn,5)) sn FROM ".$this->db->prefix."order";
        if($condition)
        {
            $sql .= " WHERE ".$condition;
        }
        $rs = $this->db->get_one($sql);
        return ($rs['sn']);
    }
	//取得订单的最大ID号，再此基础上+1
	function maxid()
	{
		$sql = "SELECT MAX(id) id FROM ".$this->db->prefix."order";
		$rs = $this->db->get_one($sql);
		if(!$rs) return '1';
		return ($rs['id']+1);
	}

	function save($data,$id=0)
	{
		if(!$data || !is_array($data)) return false;
		if($id)
		{
			return $this->db->update_array($data,"order",array("id"=>$id));
		}
		else
		{
			return $this->db->insert_array($data,"order");
		}
	}

	//存储商品信息
	function save_product($data,$id=0)
	{
		if(!$data || !is_array($data)) return false;
		if($id)
		{
			return $this->db->update_array($data,"order_product",array("id"=>$id));
		}
		else
		{
			return $this->db->insert_array($data,"order_product");
		}
	}

	function save_address($data,$id=0)
	{
		if(!$data || !is_array($data)) return false;
		if($id)
		{
			return $this->db->update_array($data,"order_address",array("id"=>$id));
		}
		else
		{
			return $this->db->insert_array($data,"order_address");
		}
	}

	//通过订单号取得单个订单信息
	function get_one_from_sn($sn,$user='')
	{
		if(!$sn) return false;
		$sql = "SELECT * FROM ".$this->db->prefix."order WHERE sn='".$sn."'";
		if($user)
		{
			$sql .= " AND user_id='".$user."'";
		}
		return $this->db->get_one($sql);
	}
   //通过包裹取得单个订单信息
    function get_one_from_package($package_id,$user='')
    {
        if(!$package_id) return false;
        $sql = "SELECT * FROM ".$this->db->prefix."order WHERE package_id='".$package_id."'";
        if($user)
        {
            $sql .= " AND user_id='".$user."'";
        }
        return $this->db->get_one($sql);
    }
	function get_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."order WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}

	function address_shipping($id)
	{
		if(!$id) return false;
		$sql = "SELECT * FROM ".$this->db->prefix."order_address WHERE order_id='".$id."' AND type_id='shipping' ORDER BY type_id ASC";
		return $this->db->get_one($sql);
	}

	function address_list($id)
	{
		if(!$id) return false;
		$sql = "SELECT * FROM ".$this->db->prefix."order_address WHERE order_id='".$id."' ORDER BY type_id ASC";
		return $this->db->get_all($sql,'type_id');
	}

	function product_one($id)
	{
		if(!$id) return false;
		return $this->db->get_one("SELECT * FROM ".$this->db->prefix."order_product WHERE id='".$id."'");
	}
	public function log_save($data)
	{
		if(!$data){
			return false;
		}
		return $this->db->insert_array($data,'order_log');
	}

	public function log_list($order_id)
	{
		$sql = "SELECT id,addtime,who,note FROM ".$this->db->prefix."order_log WHERE order_id='".$order_id."' and FROM_UNIXTIME(addtime,'%Y-%m-%d')<='".date("Y-m-d",$this->time)."' ORDER BY addtime DESC,id DESC";
		return $this->db->get_all($sql);
	}
    public function log_list_one($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_log WHERE order_id='".$id."' order by addtime desc";
        return $this->db->get_one($sql);
    }
    public function log_one($id)
    {
        $sql = "SELECT note FROM ".$this->db->prefix."order_log WHERE order_id='".$id."' order by id desc limit 1";
        return $this->db->get_one($sql);
    }
    public function express_one($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_express WHERE id='".$id."'";
        return $this->db->get_one($sql);
    }
    public function express_oid($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_express WHERE order_id='".$id."'";
        return $this->db->get_one($sql);
    }
    public function update_last_query_time($id)
    {
        $sql = "UPDATE ".$this->db->prefix."order_express SET last_query_time='".$this->time."' WHERE id='".$id."'";
        return $this->db->query($sql);
    }

    public function update_end($id)
    {
        $sql = "UPDATE ".$this->db->prefix."order_express SET is_end=1 WHERE id='".$id."'";
        return $this->db->query($sql);
    }
    public function log_del($order_id,$order_express_id,$who='')
    {
        $sql = "DELETE FROM ".$this->db->prefix."order_log WHERE order_id='".$order_id."' ";
        $sql.= " AND order_express_id='".$order_express_id."' ";
        $sql.= " AND who='".$who."'";
        return $this->db->query($sql);
    }
	function delete($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."order WHERE id=".intval($id);
        $this->db->query($sql);
        $sql = "DELETE FROM ".$this->db->prefix."order_product WHERE order_id=".intval($id);
        $this->db->query($sql);
        $sql = "DELETE FROM ".$this->db->prefix."order_address WHERE order_id=".intval($id);
        $this->db->query($sql);
        $sql = "DELETE FROM ".$this->db->prefix."order_log WHERE order_id=".intval($id);
        $this->db->query($sql);
        return true;
    }
    //导入数据查询
    public function get_import_list($condition='',$var=true)
    {
        $sql = "SELECT o.*";
        if($var){
            $sql.=",p.catename,p.brand,p.title,p.qty";
        }
        $sql.=",a.fullname,a.idcard,a.idcard_front,a.mobile,a.province,a.city,a.county,a.address";
        $sql.= " FROM ".$this->db->prefix."order o ";
        if($var){
            $sql.= "LEFT JOIN ".$this->db->prefix."order_product p ON(o.id=p.order_id) ";
        }
        $sql.= "LEFT JOIN ".$this->db->prefix."order_address a ON(o.id=a.order_id) ";
        if($condition){
            $sql .= "WHERE ".$condition." ";
        }
        $sql.= "ORDER BY o.id DESC";
        return $this->db->get_all($sql);
    }
}

?>
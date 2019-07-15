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

	public function __destruct()
	{
		parent::__destruct();
		unset($this);
	}
    public function get_list($condition='',$offset=0,$psize=30)
    {
        $sql = "SELECT o.*,u.user,u.ucode ";
        $sql.= "FROM ".$this->db->prefix."order o ";
        $sql.= "LEFT JOIN ".$this->db->prefix."user u ON(o.user_id=u.id) ";
        if($condition){
            $sql .= "WHERE ".$condition." ";
        }
        $sql.= "ORDER BY o.addtime DESC,o.id desc LIMIT ".$offset.",".$psize;
        $rslist = $this->db->get_all($sql);
        return $rslist;
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
    //身份证导出数据查询
    public function get_zip_export($condition='')
    {
        $sql = "SELECT o.*,a.fullname,a.idcard,a.idcard_front,a.idcard_back ";
        $sql.= "FROM ".$this->db->prefix."order o ";
        $sql.= "LEFT JOIN ".$this->db->prefix."order_address a ON(o.id=a.order_id) ";
        if($condition){
            $sql .= "WHERE ".$condition." ";
        }
        $sql.= "ORDER BY o.id DESC";
        return $this->db->get_all($sql);
    }
	function get_count($condition="")
	{
		$sql = "SELECT count(o.id) FROM ".$this->db->prefix."order o";
		if($condition)
		{
			$sql .= " WHERE ".$condition;
            //echo $sql;
           // exit;
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
	/*function get_one_from_sn($sn,$user='')
	{
		if(!$sn) return false;
		$sql = "SELECT * FROM ".$this->db->prefix."order WHERE sn='".$sn."'";
		if($user)
		{
			$sql .= " AND user_id='".$user."'";
		}
		return $this->db->get_one($sql);
	}*/
	function get_one($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix."order WHERE id='".$id."'";
		return $this->db->get_one($sql);
	}
    function get_cate($cid)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order WHERE cate_id='".$cid."'";
        return $this->db->get_one($sql);
    }
    public function get_cate_list($cid)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order where cate_id='".$cid."' and remove=0 ORDER BY id DESC";
        return $this->db->get_all($sql);
    }
    public function get_one_cate_list($cid)
    {
        $sql = "SELECT id,status FROM ".$this->db->prefix."order where cate_id='".$cid."' and remove=0 ORDER BY id DESC";
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
	//删除订单操作
	function delete($id)
	{
		$sql = "DELETE FROM ".$this->db->prefix."order WHERE id=".intval($id);
		$this->db->query($sql);
		$sql = "DELETE FROM ".$this->db->prefix."order_product WHERE order_id=".intval($id);
        $this->db->query($sql);
        $sql = "DELETE FROM ".$this->db->prefix."order_address WHERE order_id=".intval($id);
        $this->db->query($sql);
        $sql = "DELETE FROM ".$this->db->prefix."order_express WHERE order_id=".intval($id);
        $this->db->query($sql);
        $sql = "DELETE FROM ".$this->db->prefix."order_log WHERE order_id=".intval($id);
        $this->db->query($sql);
        $sql = "DELETE FROM ".$this->db->prefix."payment_log WHERE order_id=".intval($id);
        $this->db->query($sql);
		return true;
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
		if(!$data['who'] && $_SESSION['user_id']){
			$user = $this->model('user')->get_one($_SESSION['user_id']);
			$data['who'] = $user['user'];
		}
		if(!$data['addtime']){
			$data['addtime'] = $this->time;
		}
		return $this->db->insert_array($data,'order_log');
	}
	public function log_list($order_id)
	{
		//$sql = "SELECT id,order_express_id,addtime,who,note FROM ".$this->db->prefix."order_log WHERE order_id='".$order_id."' and FROM_UNIXTIME(addtime,'%Y-%m-%d')<='".date("Y-m-d",$this->time)."' ORDER BY addtime DESC,id DESC";
		$sql = "SELECT id,order_express_id,addtime,who,note FROM ".$this->db->prefix."order_log WHERE order_id='".$order_id."' ORDER BY addtime DESC,id DESC";
		return $this->db->get_all($sql);
	}
    public function log_list_one($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_log WHERE order_id='".$id."' order by addtime desc";
        return $this->db->get_one($sql);
    }
    public function pl_fahuo($order_id)
    {
        $sql = "SELECT id,order_express_id,addtime,who,note FROM ".$this->db->prefix."order_log WHERE order_id='".$order_id."' and order_express_id =0 ORDER BY addtime DESC,id DESC";
        return $this->db->get_all($sql);
    }
    public function log_delete($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."order_log WHERE id='".$id."'";
        $this->db->query($sql);
        return true;
    }
    public function log_dels($id,$note)
    {
        $sql = "DELETE FROM ".$this->db->prefix."order_log WHERE order_id in(".$id.") and note='".$note."'";
        $this->db->query($sql);
        return true;
    }
    public function log_del($order_id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."order_log WHERE order_id='".$order_id."' ";
        return $this->db->query($sql);
    }
    public function log_one($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_log WHERE id='".$id."'";
        return $this->db->get_one($sql);
    }
    public function log_one_from_order($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_log WHERE order_id='".$id."' and order_express_id=0 order by id desc";
        return $this->db->get_one($sql);
    }
    public function log_one_from_note($note,$id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_log WHERE note='".$note."' and order_id=".$id;
        return $this->db->get_one($sql);
    }
    public function express_all($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_express WHERE order_id='".$id."' AND express_id!=0 ";
        $sql.= "ORDER BY addtime ASC";
        return $this->db->get_all($sql);
    }

    public function express_save($data,$id="")
    {
        if(!$data || !is_array($data)) return false;
        if($id)
        {
            return $this->db->update_array($data,"order_express",array("id"=>$id));
        }
        else
        {
            return $this->db->insert_array($data,"order_express","replace");
        }
       // return $this->db->insert_array($data,'order_express');
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
    public function express_delete($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."order_express WHERE order_id='".$id."'";
        $this->db->query($sql);
        $sql = "DELETE FROM ".$this->db->prefix."order_log WHERE order_id='".$id."' and order_express_id<>0";
        $this->db->query($sql);
        return true;
    }
    function get_from_sn($sn,$user='')
    {
        if(!$sn) return false;
        $sql = "SELECT * FROM ".$this->db->prefix."order WHERE sn ='".$sn."'";
        if($user)
        {
            $sql .= " AND user_id='".$user."'";
        }
        return $this->db->get_one($sql);
    }
    public function get_order($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order WHERE id in(".$id.") ORDER BY id DESC";
        return $this->db->get_all($sql);
    }
	public function update_order_status($id,$status='')
    {
        $sql = "UPDATE ".$this->db->prefix."order SET status='".$status."' WHERE id='".$id."'";
        return $this->db->query($sql);
    }
    public function GetOrder($sn)
    {
        $sql = "SELECT o.cate_id,o.jingzhong,o.status,a.fullname,a.idcard";
        $sql.= " FROM ".$this->db->prefix."order o";
        $sql.= " LEFT JOIN ".$this->db->prefix."order_address a ON(o.id=a.order_id)";
        $sql .= "WHERE sn='".$sn."'";
        return $this->db->get_one($sql);
    }
	//后台快递重新添加快递判断
	/*public function getOneLog($id)
    {
        $sql = "SELECT * FROM ".$this->db->prefix."order_log WHERE order_id='".$id."' and order_express_id<>0";
        return $this->db->get_one($sql);
    }*/
	public function deleteExpress($id)
    {
        $sql = "DELETE FROM ".$this->db->prefix."order_express WHERE id='".$id."'";
        $this->db->query($sql);
        $sql = "DELETE FROM ".$this->db->prefix."order_log WHERE order_express_id='".$id."'";
        $this->db->query($sql);
        return true;
    }
}
?>